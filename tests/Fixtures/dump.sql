INSERT INTO users(id, name, email, questionnaires_count, points, password, reset_token, role_id, subscribed_before, created_at, updated_at)
    VALUES
(1, 'Diego Alvarez', 'admin@example.net', null, null, '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy', null, 1, null, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(2, 'Ivan Dubinov', 'ivan.dubinov@example.net', 1, 0, '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy', null, 2, null, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(3, 'Peter Griffin', 'peter.griffin@example.net', 3, 0, '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy', null, 2, '2018-12-02 16:00:00', '2018-12-02 16:00:00', '2018-12-01 16:00:00'),
(4, 'Megan Griffin', 'megan.griffin@example.net', 20, 100, '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy', null, 2, null, '2018-12-02 16:00:00', '2018-12-01 16:00:00'),
(5, 'Louise Griffin', 'louise.griffin@example.net', 11, 100, '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy', null, 2, null, '2018-12-02 16:00:00', '2018-12-01 16:00:00');

INSERT INTO questionnaires(id, name, description, success_score, result_type, content, type_id, is_active, created_at, updated_at)
    VALUES
(1, 'Skills test', 'some words', 10, 'avg', '{"questions":[{"text": "first"},{"text": "second"}], "answers": [{"text": "first","points": "2"},{"text": "second","points": "4"}]}', 1, 1, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(2, 'Communication test', 'some words', 100, 'sum', '{"questions":[{"text": "first"},{"text": "second"}], "answers": [{"text": "first","points": "2"},{"text": "second","points": "4"}]}', 2, 0, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(3, 'IQ test', 'some words', 12, 'avg', '{"questions":[{"text": "first"},{"text": "second"}], "answers": [{"text": "first","points": "2"},{"text": "second","points": "4"}]}', 3, 1, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(4, 'IQ test inactive', 'some words', 70, 'sum', '{"questions":[{"text": "first"},{"text": "second"}], "answers": [{"text": "first","points": "2"},{"text": "second","points": "4"}]}', 4, 1, '2018-12-01 16:00:00', '2018-12-01 16:00:00');

INSERT INTO questionnaire_types(id, name)
    VALUES
(1, 'intellect'),
(2, 'agility'),
(3, 'spirit'),
(4, 'straight'),
(5, 'experience');

INSERT INTO plans(id, name, price, points, type, description, is_active, created_at, updated_at)
    VALUES
(1, 'light', 200, 100, 'purchase', '["line 1", "line 2"]', true, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(2, 'half-light', 300, 200, 'purchase', '["line 1", "line 2"]', false, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(3, 'medium', 100, 400, 'purchase', '["line 1", "line 2"]', true, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(4, 'premium', 100, 500, 'purchase', '["line 1", "line 2"]', true, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(5, 'subscription', 600, 500, 'subscription', '["line 1", "line 2"]', true, '2018-12-01 16:00:00', '2018-12-01 16:00:00');

INSERT INTO questionnaires_results(id, content, email, recipient_name, score, access_hash, is_passed, questionnaire_id, user_id, expired_at)
    VALUES
(1, '[]', 'some.email@recipient.no', 'first', 10, 'qwerty', true, 1, 2, '2018-12-01 16:00:00'),
(2, '[]', 'some.email@recipient.no', 'second', 9, 'qwertyqwe', true, 1, 3, '2018-12-01 16:00:00'),
(3, '[]', 'some.email@recipient.no', 'third', null, 'qwertyreqre', false, 1, 3, '2018-12-01 16:00:00');

INSERT INTO payments(id, user_id, payment_id, plan_id, is_paid, status, amount, currency, description, started_at, created_at, updated_at)
    VALUES
(1, 4, '22d6d597-000f-5000-9000-145f6df21d88', 1, false, 'pending', 200.00, 'RUB', 'payment desc', '2018-07-10 14:27:54', '2018-07-10 14:27:54', '2018-07-10 14:27:54');

INSERT INTO payment_transactions(id, user_id, plan_id, token, created_at, updated_at)
    VALUES
(1, 3, 1, '22d6d597-000f-5000-9000-145f6df21d6f', '2019-01-02 12:00:00', '2019-01-02 12:00:00');
