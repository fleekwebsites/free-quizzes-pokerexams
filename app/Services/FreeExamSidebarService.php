<?php

namespace App\Services;

use App\Models\FreeExam;
use Illuminate\Support\Collection;

class FreeExamSidebarService
{
    /**
     * Section A: the paired free quiz (quiz 1 ↔ quiz 2) for the same course/exam line.
     */
    public function similarExam(FreeExam $current): ?FreeExam
    {
        if (preg_match('/^(.*)-quiz-(\d+)$/', $current->slug, $matches)) {
            $siblingNum = $matches[2] === '1' ? '2' : '1';
            $siblingSlug = $matches[1].'-quiz-'.$siblingNum;

            $sibling = FreeExam::query()
                ->where('subdivision_id', $current->subdivision_id)
                ->where('slug', $siblingSlug)
                ->first();

            if ($sibling) {
                return $sibling;
            }
        }

        return FreeExam::query()
            ->where('subdivision_id', $current->subdivision_id)
            ->where('id', '!=', $current->id)
            ->orderBy('id')
            ->first();
    }

    /**
     * Section B: up to 5 other exams in the same subdivision (wraparound algorithm from reindexing plan §3.5).
     */
    public function otherExams(FreeExam $current, int $limit = 5): Collection
    {
        $similar = $this->similarExam($current);

        $candidates = FreeExam::query()
            ->where('subdivision_id', $current->subdivision_id)
            ->where('id', '>', $current->id)
            ->when($similar, fn ($query) => $query->where('id', '!=', $similar->id))
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($candidates->count() < $limit) {
            $needed = $limit - $candidates->count();
            $excludeIds = $candidates->pluck('id')
                ->push($current->id)
                ->when($similar, fn ($ids) => $ids->push($similar->id))
                ->all();

            $wraparound = FreeExam::query()
                ->where('subdivision_id', $current->subdivision_id)
                ->whereNotIn('id', $excludeIds)
                ->orderBy('id')
                ->limit($needed)
                ->get();

            $candidates = $candidates->concat($wraparound);
        }

        return $candidates->take($limit)->values();
    }
}
