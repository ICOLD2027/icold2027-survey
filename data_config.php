<?php
// ICOLD 2027 Daejeon - Survey question / option definitions.
// Shared by index.php (form rendering) and admin/index.php (stats labels).
//
// 2026-09 개선안 반영:
//   - Q1 장소: 대전 / 서울 / DMZ / 제주 (pre-post 투어 코스와 연계)
//   - Q2 음식: 치킨 / 비빔밥 / K-BBQ / 불고기
//   - Q3 활동: 템플스테이 / 자연·유적 방문 / 전통문화체험 / 미술관
//     (시티투어 / 동반자투어 / 기술투어와 연계)
//
// NOTE: options[].img for 'DMZ', 'Templestay', and 'Art museum / gallery'
// point to placeholder illustrations (images/q1-dmz.jpg, images/q3-templestay.jpg,
// images/q3-gallery.jpg) generated locally since no source photo was provided.
// Swap these three files for real photos before the survey goes live --
// everything else reuses the site's existing real photos.

$SURVEY_QUESTIONS = [
    [
        'code' => 'q1',
        'title_en' => 'If you visit Korea, where would you most like to go?',
        'title_ko' => '한국을 방문한다면 가장 가보고 싶은 장소가 어디인가요?',
        'note_en' => 'Linked to the pre/post-conference tour courses.',
        'note_ko' => 'pre-post 투어 코스와 연계됩니다.',
        'options' => [
            ['code' => 'a', 'en' => 'Daejeon — Sungsimdang Bakery, Expo Science Park, Hanbat Arboretum (conference venue)', 'ko' => '대전 - 성심당, 엑스포 과학공원, 한밭수목원 (행사 개최지)', 'img' => 'q1-a.jpg'],
            ['code' => 'b', 'en' => 'Seoul — Myeongdong, Gwanghwamun, Gyeongbokgung Palace, N Seoul Tower', 'ko' => '서울 - 명동, 광화문, 경복궁, N서울타워', 'img' => 'q1-b.jpg'],
            ['code' => 'c', 'en' => 'DMZ — Panmunjom (JSA), Imjingak, the 3rd Infiltration Tunnel', 'ko' => 'DMZ - 판문점(JSA), 임진각, 제3땅굴', 'img' => 'q1-dmz.jpg'],
            ['code' => 'd', 'en' => 'Jeju — Seongsan Ilchulbong, Hallasan, Udo Island', 'ko' => '제주 - 성산일출봉, 한라산, 우도', 'img' => 'q1-e.jpg'],
        ],
    ],
    [
        'code' => 'q2',
        'title_en' => 'What Korean food would you most like to try?',
        'title_ko' => '한국에서 가장 먹어보고 싶은 음식이 있다면 무엇인가요?',
        'note_en' => 'Used to plan meal menus and special food corners.',
        'note_ko' => '식사 메뉴 및 특별 코너 마련에 활용됩니다.',
        'options' => [
            ['code' => 'a', 'en' => 'K-Chicken', 'ko' => 'K-치킨', 'img' => 'q2-a.jpg'],
            ['code' => 'b', 'en' => 'Bibimbap', 'ko' => '비빔밥', 'img' => 'q2-c.jpg'],
            ['code' => 'c', 'en' => 'K-BBQ / Meat (Samgyeopsal, Galbi, etc.)', 'ko' => 'K-바비큐/고기 (삼겹살, 갈비 등)', 'img' => 'q2-e.jpg'],
            ['code' => 'd', 'en' => 'Bulgogi', 'ko' => '불고기', 'img' => 'q2-d.jpg'],
        ],
    ],
    [
        'code' => 'q3',
        'title_en' => 'What would you like to experience in Korea?',
        'title_ko' => '한국에서 체험해보고 싶은 것은 무엇인가요?',
        'note_en' => 'Linked to the city tour, companion tour, and technical tour options.',
        'note_ko' => '시티투어, 동반자투어, 기술투어와 연계됩니다.',
        'options' => [
            ['code' => 'a', 'en' => 'Templestay', 'ko' => '템플스테이', 'img' => 'q3-templestay.jpg'],
            ['code' => 'b', 'en' => 'Nature & heritage site visits (scenic nature, historic trails, etc.)', 'ko' => '자연·유적 방문 (자연경관, 역사 탐방로 등)', 'img' => 'q3-c.jpg'],
            ['code' => 'c', 'en' => 'Traditional culture experience (hanbok, palaces & historic sites, tea ceremony, etc.)', 'ko' => '전통문화체험 (한복, 고궁·유적지, 다도 등)', 'img' => 'q3-d.jpg'],
            ['code' => 'd', 'en' => 'Art museum / gallery', 'ko' => '미술관', 'img' => 'q3-gallery.jpg'],
        ],
    ],
];
