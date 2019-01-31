INSERT INTO questionnaires(id, name, description, success_score, max_score, content, type_id, is_active, created_at, updated_at)
    VALUES
(5, 'intellect test', 'some words', 20, 30,
    '{"questions":[
        {"text": "some text 1"},
        {"text": "some text 2"},
        {"text": "some text 3"},
        {"text": "some text 4"},
        {"text": "some text 5"},
        {"text": "some text 6"},
        ],
      "answers": [
        {"text": "1 answer","points": "1"},
        {"text": "2 answer","points": "2"},
        {"text": "3 answer","points": "3"},
        {"text": "4 answer","points": "4"},
        {"text": "5 answer","points": "5"},
        ]
    }', 1, 1, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),

(6, 'agility test',   'some words', 33, 42,
    '{"questions":[
        {"text": "some text 1"},
        {"text": "some text 2"},
        {"text": "some text 3"},
        {"text": "some text 4"},
        {"text": "some text 5"},
        {"text": "some text 6"},
        ],
      "answers": [
        {"text": "1 answer","points": "3"},
        {"text": "2 answer","points": "4"},
        {"text": "3 answer","points": "5"},
        {"text": "4 answer","points": "6"},
        {"text": "5 answer","points": "7"},
        ]
    }', 2, 1, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),

(7, 'spirit test',    'some words', 108, 150,
    '{"questions":[
        {"text": "some text 1"},
        {"text": "some text 2"},
        {"text": "some text 3"},
        {"text": "some text 4"},
        {"text": "some text 5"},
        {"text": "some text 6"},
        ],
      "answers": [
        {"text": "1 answer","points": "5"},
        {"text": "2 answer","points": "10"},
        {"text": "3 answer","points": "15"},
        {"text": "4 answer","points": "20"},
        {"text": "5 answer","points": "25"},
        ]
    }', 3, 1, '2018-12-01 16:00:00', '2018-12-01 16:00:00');

INSERT INTO questionnaires_results(id, email, recipient_name, vacancy, score, access_hash, is_passed, questionnaire_id, user_id, content)
    VALUES
(4, 'mmax@recipient.no', 'max', 'doctor', 23, 'mmax', 1, 5, 4,
    '[
        {"index": 0, "result": "4"}
        {"index": 1, "result": "4"}
        {"index": 2, "result": "4"}
        {"index": 3, "result": "4"}
        {"index": 4, "result": "4"}
        {"index": 5, "result": "3"}
    ]'
),
(5, 'mmax@recipient.no', 'max', 'doctor', 36, 'mmax', 1, 6, 4,
    '[
        {"index": 0, "result": "5"}
        {"index": 1, "result": "6"}
        {"index": 2, "result": "7"}
        {"index": 3, "result": "6"}
        {"index": 4, "result": "6"}
        {"index": 5, "result": "6"}
    ]'
),
(6, 'mmax@recipient.no', 'max', 'doctor', 70, 'mmax', 1, 7, 4,
    '[
        {"index": 0, "result": "10"}
        {"index": 1, "result": "10"}
        {"index": 2, "result": "10"}
        {"index": 3, "result": "10"}
        {"index": 4, "result": "15"}
        {"index": 5, "result": "15"}
    ]'
),
(7, 'ssax@recipient.no', 'diego', 'doctor', 17, 'mmax', 1, 5, 4,
    '[
        {"index": 0, "result": "4"}
        {"index": 1, "result": "4"}
        {"index": 2, "result": "4"}
        {"index": 3, "result": "1"}
        {"index": 4, "result": "1"}
        {"index": 5, "result": "3"}
    ]'
),
(8, 'ssax@recipient.no', 'diego', 'doctor', 44, 'mmax', 1, 6, 4,
    '[
        {"index": 0, "result": "7"}
        {"index": 1, "result": "7"}
        {"index": 2, "result": "7"}
        {"index": 3, "result": "6"}
        {"index": 4, "result": "7"}
        {"index": 5, "result": "6"}
    ]'
),
(9, 'ssax@recipient.no', 'diego', 'doctor', 75, 'mmax', 1, 7, 4,
    '[
        {"index": 0, "result": "10"}
        {"index": 1, "result": "10"}
        {"index": 2, "result": "10"}
        {"index": 3, "result": "15"}
        {"index": 4, "result": "15"}
        {"index": 5, "result": "15"}
    ]'
),
(10, 'pirex@recipient.no', 'diego', 'manager', 11, 'ddd', 1, 5, 4,
    '[
        {"index": 0, "result": "1"}
        {"index": 1, "result": "1"}
        {"index": 2, "result": "4"}
        {"index": 3, "result": "1"}
        {"index": 4, "result": "1"}
        {"index": 5, "result": "3"}
    ]'
),
(11, 'pirex@recipient.no', 'diego', 'manager', 36, 'ddd', 1, 6, 4,
    '[
        {"index": 0, "result": "6"}
        {"index": 1, "result": "6"}
        {"index": 2, "result": "6"}
        {"index": 3, "result": "6"}
        {"index": 4, "result": "6"}
        {"index": 5, "result": "6"}
    ]'
),
(12, 'pirex@recipient.no', 'diego', 'manager', 120, 'ddd', 1, 7, 4,
    '[
        {"index": 0, "result": "20"}
        {"index": 1, "result": "20"}
        {"index": 2, "result": "20"}
        {"index": 3, "result": "20"}
        {"index": 4, "result": "20"}
        {"index": 5, "result": "20"}
    ]'
),
(13, 'llady@recipient.no', 'madonna', 'manager', 22, 'mmad', 1, 5, 4,
    '[
        {"index": 0, "result": "3"}
        {"index": 1, "result": "4"}
        {"index": 2, "result": "4"}
        {"index": 3, "result": "4"}
        {"index": 4, "result": "4"}
        {"index": 5, "result": "3"}
    ]'
),
(14, 'llady@recipient.no', 'madonna', 'manager', 30, 'mmad', 1, 6, 4,
    '[
        {"index": 0, "result": "5"}
        {"index": 1, "result": "5"}
        {"index": 2, "result": "5"}
        {"index": 3, "result": "5"}
        {"index": 4, "result": "5"}
        {"index": 5, "result": "5"}
    ]'
),
(15, 'llady@recipient.no', 'madonna', 'manager', 130, 'mmad', 1, 7, 4,
    '[
        {"index": 0, "result": "25"}
        {"index": 1, "result": "25"}
        {"index": 2, "result": "20"}
        {"index": 3, "result": "20"}
        {"index": 4, "result": "20"}
        {"index": 5, "result": "20"}
    ]'
);
