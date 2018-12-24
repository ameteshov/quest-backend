INSERT INTO users(id, name, email, plan_id, password, reset_token, role_id, created_at, updated_at)
    VALUES
(1, 'Diego Alvarez', 'admin@example.net', null, '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy', null, 1, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(2, 'Ivan Dubinov', 'ivan.dubinov@example.net', 1, '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy', null, 2, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(3, 'Peter Griffin', 'peter.griffin@example.net', 1, '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy', null, 2, '2018-12-02 16:00:00', '2018-12-01 16:00:00'),
(4, 'Megan Griffin', 'megan.griffin@example.net', 1, '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy', null, 2, '2018-12-02 16:00:00', '2018-12-01 16:00:00'),
(5, 'Louise Griffin', 'louise.griffin@example.net', 1, '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy', null, 2, '2018-12-02 16:00:00', '2018-12-01 16:00:00');

INSERT INTO questionnaires(id, name, content, created_at, updated_at)
    VALUES
(1, 'Skills test', '[{"title":"Question 1"},{"title":"Question 2"}]', '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(2, 'Communication test', '[{"title":"Question 1"},{"title":"Question 2"}]', '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(3, 'IQ test', '[{"title":"Question 1"},{"title":"Question 2"}]', '2018-12-01 16:00:00', '2018-12-01 16:00:00');

INSERT INTO plans(id, name, price, points, created_at, updated_at)
    VALUES
(1, 'light', 200, 1, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(2, 'half-light', 300, 200, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(3, 'medium', 100, 400, '2018-12-01 16:00:00', '2018-12-01 16:00:00'),
(4, 'premium', 100, 500, '2018-12-01 16:00:00', '2018-12-01 16:00:00');

INSERT INTO questionnaires_results(id, content, email, recipient_name, access_hash, is_passed, questionnaire_id, user_id)
    VALUES
(1, '[]', 'some.email@recipient.no', 'first', 'qwerty', true, 1, 2),
(2, '[]', 'some.email@recipient.no', 'second', 'qwertyqwe', true, 1, 3),
(3, '[]', 'some.email@recipient.no', 'third', 'qwertyreqre', false, 1, 3);