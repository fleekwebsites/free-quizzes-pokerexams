<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Main platform (pokerexams.com)
    |--------------------------------------------------------------------------
    | Used only for intentional bridge links back to the paid platform.
    */
    'main_site_url' => rtrim(env('MAIN_SITE_URL', 'https://pokerexams.com'), '/'),

    /*
    |--------------------------------------------------------------------------
    | Question images (this subdomain only)
    |--------------------------------------------------------------------------
    | CSV `image` column and image-type choices store filenames only.
    | Place files under public/img/questions/ (or the path below).
    */
    'question_images_path' => 'img/questions',

    'whatsapp' => [
        'phone' => env('WHATSAPP_PHONE', '15107718152'),
        'message' => env('WHATSAPP_MESSAGE', 'Hello...'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Static course slug → subdivision (school) slug mapping
    |--------------------------------------------------------------------------
    | Used while category cards still reference main-site course slugs.
    */
    'course_subdivision_map' => [
        'certified-anti-money-laundering-specialist' => 'business-and-finance-certification-exam-prep-and-study-guides',
        'certified-banking-professional' => 'business-and-finance-certification-exam-prep-and-study-guides',
        'licensed-securities-professional-exam' => 'business-and-finance-certification-exam-prep-and-study-guides',
        'securities-industry-essentials-sie-exam' => 'business-and-finance-certification-exam-prep-and-study-guides',
        'accuplacer' => 'college-admissions-and-placement-test-prep',
        'college-level-examination-program-clep' => 'college-admissions-and-placement-test-prep',
        'graduate-management-admission-test-gmat' => 'college-admissions-and-placement-test-prep',
        'graduate-record-examination-gre' => 'college-admissions-and-placement-test-prep',
        'texas-success-initiative-assessment-2-tsia2' => 'college-admissions-and-placement-test-prep',
        'western-governors-university-wgu-exams' => 'college-admissions-and-placement-test-prep',
        'civic-literacy-exams' => 'high-school-equivalency-and-diploma-test-prep',
        'general-educational-development-ged' => 'high-school-equivalency-and-diploma-test-prep',
        'hiset' => 'high-school-equivalency-and-diploma-test-prep',
        'life-insurance-producer' => 'insurance-licensing-exam-prep-and-practice-tests',
        'comptia' => 'it-and-tech-certification-exam-prep',
        'linux-certification-exam' => 'it-and-tech-certification-exam-prep',
        'certified-clinical-medical-assistant-exams' => 'healthcare-and-medical-certification-test-prep',
        'counselor-certification-exams' => 'healthcare-and-medical-certification-test-prep',
        'pharmacy-technician-certification-exam-ptce' => 'healthcare-and-medical-certification-test-prep',
        'phlebotomy-technician-certificate-exams' => 'healthcare-and-medical-certification-test-prep',
        'ati-teas-7' => 'nursing-entrance-and-certification-exam-prep',
        'certified-hospice-and-palliative-nurse-exams' => 'nursing-entrance-and-certification-exam-prep',
        'certified-nursing-assistant-cna-exams' => 'nursing-entrance-and-certification-exam-prep',
        'hesi-a2' => 'nursing-entrance-and-certification-exam-prep',
        'nursing-entrance-exam-nex' => 'nursing-entrance-and-certification-exam-prep',
        'wonderlic-test' => 'cognitive-ability-and-aptitude-test-prep',
        'certified-barber-licensing-exam' => 'professional-trades-and-licensing-exam-prep',
        'certified-protection-professional' => 'professional-trades-and-licensing-exam-prep',
        'contractor-license-exams' => 'professional-trades-and-licensing-exam-prep',
        'immigration-representative-consultant' => 'professional-trades-and-licensing-exam-prep',
        'plumber-licensing-exams' => 'professional-trades-and-licensing-exam-prep',
        'project-management-professional-certification-exam' => 'professional-trades-and-licensing-exam-prep',
        'licensed-mortgage-originators-exams' => 'real-estate-license-exam-prep',
        'salesperson-and-broker-license-exam' => 'real-estate-license-exam-prep',
        'praxis' => 'teacher-certification-and-licensure-exam-prep',
    ],

];
