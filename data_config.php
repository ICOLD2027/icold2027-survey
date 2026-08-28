<?php
// ICOLD 2027 Daejeon - Survey question / option definitions.
// Shared by index.php (form rendering) and admin/index.php (stats labels).

$SURVEY_QUESTIONS = [
    [
        'code' => 'q1',
        'title_en' => 'If you visit Korea, where would you most like to go?',
        'title_ko' => '한국을 방문한다면 가장 가보고 싶은 장소가 어디인가요?',
        'options' => [
            ['code' => 'a', 'en' => 'Daejeon — Sungsimdang Bakery, Expo Science Park, Hanbat Arboretum (conference venue)', 'ko' => '대전 - 성심당, 엑스포 과학공원, 한밭수목원 (행사 개최지)', 'img' => 'q1-a.jpg'],
            ['code' => 'b', 'en' => 'Seoul — Myeongdong, Gwanghwamun, Gyeongbokgung Palace, N Seoul Tower', 'ko' => '서울 - 명동, 광화문, 경복궁, N서울타워', 'img' => 'q1-b.jpg'],
            ['code' => 'c', 'en' => 'Busan — Haeundae, Gwangalli, Gamcheon Culture Village', 'ko' => '부산 - 해운대, 광안리, 감천문화마을', 'img' => 'q1-c.jpg'],
            ['code' => 'd', 'en' => 'Gyeonggi — Suwon Hwaseong Fortress, Everland', 'ko' => '경기 - 수원화성, 에버랜드', 'img' => 'q1-d.jpg'],
        ],
    ],
    [
        'code' => 'q2',
        'title_en' => 'What Korean food would you most like to try?',
        'title_ko' => '한국에서 가장 먹어보고 싶은 음식이 있다면 무엇인가요?',
        'options' => [
            ['code' => 'a', 'en' => 'K-Chicken', 'ko' => 'K-치킨', 'img' => 'q2-a.jpg'],
            ['code' => 'b', 'en' => 'Kimchi', 'ko' => '김치', 'img' => 'q2-b.jpg'],
            ['code' => 'c', 'en' => 'Bibimbap', 'ko' => '비빔밥', 'img' => 'q2-c.jpg'],
            ['code' => 'd', 'en' => 'K-BBQ / Meat (Samgyeopsal, Galbi, etc.)', 'ko' => 'K-바비큐/고기 (삼겹살, 갈비 등)', 'img' => 'q2-e.jpg'],
        ],
    ],
    [
        'code' => 'q3',
        'title_en' => 'What would you like to experience in Korea?',
        'title_ko' => '한국에서 체험해보고 싶은 것은 무엇인가요?',
        'options' => [
            ['code' => 'a', 'en' => 'Culinary (Korean restaurants, K-street food, Korean cooking classes, etc.)', 'ko' => '식도락 (한식 맛집, K-스트리트푸드, 한식 쿠킹 등)', 'img' => 'q3-a.jpg'],
            ['code' => 'b', 'en' => 'Shopping / Lifestyle (K-beauty, jjimjilbang & spa, personal color diagnosis, etc.)', 'ko' => '쇼핑/라이프스타일 (K-뷰티, 찜질방·스파, 퍼스널컬러 진단 등)', 'img' => 'q3-b.jpg'],
            ['code' => 'c', 'en' => 'Nature / Healing (scenic nature, mountain trekking, walking tours, etc.)', 'ko' => '자연/힐링 (자연경관, 산·트레킹, 걷기여행 등)', 'img' => 'q3-c.jpg'],
            ['code' => 'd', 'en' => 'Traditional Culture (palaces & historic sites, hanbok experience, templestay, etc.)', 'ko' => '전통문화 (고궁·역사유적, 한복 체험, 템플스테이 등)', 'img' => 'q3-d.jpg'],
        ],
    ],
];
