#!/usr/bin/env python3
"""Replace hardcoded pokerexams.com URLs in Blade views with subdomain helpers."""

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
VIEWS = [
    ROOT / "resources/views/index.blade.php",
    ROOT / "resources/views/subject.blade.php",
    ROOT / "resources/views/quiz.blade.php",
    ROOT / "resources/views/partials/header.blade.php",
    ROOT / "resources/views/partials/sidebar.blade.php",
]

TEACHING = "teacher-certification-and-licensure-exam-prep"

COURSE_MAP = {
    "certified-anti-money-laundering-specialist": "business-and-finance-certification-exam-prep-and-study-guides",
    "certified-banking-professional": "business-and-finance-certification-exam-prep-and-study-guides",
    "licensed-securities-professional-exam": "business-and-finance-certification-exam-prep-and-study-guides",
    "securities-industry-essentials-sie-exam": "business-and-finance-certification-exam-prep-and-study-guides",
    "accuplacer": "college-admissions-and-placement-test-prep",
    "college-level-examination-program-clep": "college-admissions-and-placement-test-prep",
    "graduate-management-admission-test-gmat": "college-admissions-and-placement-test-prep",
    "graduate-record-examination-gre": "college-admissions-and-placement-test-prep",
    "texas-success-initiative-assessment-2-tsia2": "college-admissions-and-placement-test-prep",
    "western-governors-university-wgu-exams": "college-admissions-and-placement-test-prep",
    "civic-literacy-exams": "high-school-equivalency-and-diploma-test-prep",
    "general-educational-development-ged": "high-school-equivalency-and-diploma-test-prep",
    "hiset": "high-school-equivalency-and-diploma-test-prep",
    "life-insurance-producer": "insurance-licensing-exam-prep-and-practice-tests",
    "comptia": "it-and-tech-certification-exam-prep",
    "linux-certification-exam": "it-and-tech-certification-exam-prep",
    "certified-clinical-medical-assistant-exams": "healthcare-and-medical-certification-test-prep",
    "counselor-certification-exams": "healthcare-and-medical-certification-test-prep",
    "pharmacy-technician-certification-exam-ptce": "healthcare-and-medical-certification-test-prep",
    "phlebotomy-technician-certificate-exams": "healthcare-and-medical-certification-test-prep",
    "ati-teas-7": "nursing-entrance-and-certification-exam-prep",
    "certified-hospice-and-palliative-nurse-exams": "nursing-entrance-and-certification-exam-prep",
    "certified-nursing-assistant-cna-exams": "nursing-entrance-and-certification-exam-prep",
    "hesi-a2": "nursing-entrance-and-certification-exam-prep",
    "nursing-entrance-exam-nex": "nursing-entrance-and-certification-exam-prep",
    "wonderlic-test": "cognitive-ability-and-aptitude-test-prep",
    "certified-barber-licensing-exam": "professional-trades-and-licensing-exam-prep",
    "certified-protection-professional": "professional-trades-and-licensing-exam-prep",
    "contractor-license-exams": "professional-trades-and-licensing-exam-prep",
    "immigration-representative-consultant": "professional-trades-and-licensing-exam-prep",
    "plumber-licensing-exams": "professional-trades-and-licensing-exam-prep",
    "project-management-professional-certification-exam": "professional-trades-and-licensing-exam-prep",
    "licensed-mortgage-originators-exams": "real-estate-license-exam-prep",
    "salesperson-and-broker-license-exam": "real-estate-license-exam-prep",
    "praxis": TEACHING,
}


def replace_content(content: str) -> str:
    # Interactions (local subdomain)
    content = content.replace(
        "https://pokerexams.com/interaction/flag",
        "{{ route('interaction.flag') }}",
    )
    content = content.replace(
        "https://pokerexams.com/interaction/rate",
        "{{ route('interaction.rate') }}",
    )

    # Assets
    content = content.replace(
        "https://pokerexams.com/img/logo.png",
        "{{ asset('img/logo.png') }}",
    )

    # Main-site account bridges
    content = content.replace(
        "https://pokerexams.com/dashboard",
        "{{ main_url('/dashboard') }}",
    )
    content = content.replace(
        "https://pokerexams.com/my-courses",
        "{{ main_url('/my-courses') }}",
    )
    content = content.replace(
        "https://pokerexams.com/profile",
        "{{ main_url('/profile') }}",
    )
    content = content.replace(
        "https://pokerexams.com/login",
        "{{ main_url('/login') }}",
    )

    # Subscribe bridge to main site
    content = re.sub(
        r"https://pokerexams\.com/subscribe/([^\"'\s>]+)",
        r"{{ main_url('/subscribe/\1') }}",
        content,
    )

    # Retake on main site
    content = re.sub(
        r"https://pokerexams\.com/library/courses/exams/questions/([^\"'\s/]+)/retake",
        r"{{ main_url('/library/courses/exams/questions/\1/retake') }}",
        content,
    )

    # Question/exam pages -> free-quizzes exam URLs (teaching demo subdivision)
    content = re.sub(
        r"https://pokerexams\.com/library/courses/exams/questions/([^\"'\s/]+)",
        lambda m: f"{{{{ exam_url('{TEACHING}', '{m.group(1)}') }}}}",
        content,
    )

    # Course pages -> subdivision pages on this subdomain
    for course, subdivision in COURSE_MAP.items():
        content = content.replace(
            f"https://pokerexams.com/library/courses/exams/{course}",
            f"{{{{ route('subdivision.show', '{subdivision}') }}}}",
        )

    # Library index on this subdomain
    content = content.replace(
        "https://pokerexams.com/library",
        "{{ route('library.index') }}",
    )

    # Standalone home links on this subdomain
    content = re.sub(
        r'href="https://pokerexams\.com"',
        'href="{{ route(\'library.index\') }}"',
        content,
    )
    content = re.sub(
        r'"item": "https://pokerexams\.com"',
        '"item": "{{ route(\'library.index\') }}"',
        content,
    )
    content = re.sub(
        r"onclick=\"window\.location\.href='https://pokerexams\.com/library/courses/exams/questions/([^']+)'\"",
        lambda m: f"onclick=\"window.location.href='{{{{ exam_url('{TEACHING}', '{m.group(1)}') }}}}'\"",
        content,
    )

    return content


def main() -> None:
    for path in VIEWS:
        if not path.exists():
            print(f"SKIP {path}")
            continue
        original = path.read_text(encoding="utf-8")
        updated = replace_content(original)
        if updated != original:
            path.write_text(updated, encoding="utf-8")
            count = original.count("pokerexams.com") - updated.count("pokerexams.com")
            print(f"UPDATED {path.name}: removed ~{count} main-domain refs")
        else:
            remaining = updated.count("pokerexams.com")
            print(f"UNCHANGED {path.name} ({remaining} refs remain)")


if __name__ == "__main__":
    main()
