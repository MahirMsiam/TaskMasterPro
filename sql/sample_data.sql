-- Sample data for TaskMaster Pro

INSERT INTO organizations (org_id, org_name, subscription_plan, max_users)
VALUES (1, 'Acme Innovations', 'pro', 25);

INSERT INTO users (user_id, email, password_hash, full_name, role, organization_id, email_verified)
VALUES
    (1, 'admin@taskmasterpro.test', '$2y$12$mmAWYNDHvBAOaKEaO3RrA.c3BGZXmSb4q2C2l.6sV4Hw0QBbtHvLC', 'Avery Admin', 'super_admin', 1, 1),
    (2, 'pm1@taskmasterpro.test', '$2y$12$mmAWYNDHvBAOaKEaO3RrA.c3BGZXmSb4q2C2l.6sV4Hw0QBbtHvLC', 'Morgan Manager', 'project_manager', 1, 1),
    (3, 'pm2@taskmasterpro.test', '$2y$12$mmAWYNDHvBAOaKEaO3RrA.c3BGZXmSb4q2C2l.6sV4Hw0QBbtHvLC', 'Casey Coordinator', 'project_manager', 1, 1),
    (4, 'member1@taskmasterpro.test', '$2y$12$mmAWYNDHvBAOaKEaO3RrA.c3BGZXmSb4q2C2l.6sV4Hw0QBbtHvLC', 'Taylor Team', 'team_member', 1, 1),
    (5, 'member2@taskmasterpro.test', '$2y$12$mmAWYNDHvBAOaKEaO3RrA.c3BGZXmSb4q2C2l.6sV4Hw0QBbtHvLC', 'Jordan Dev', 'team_member', 1, 1),
    (6, 'member3@taskmasterpro.test', '$2y$12$mmAWYNDHvBAOaKEaO3RrA.c3BGZXmSb4q2C2l.6sV4Hw0QBbtHvLC', 'Riley Designer', 'team_member', 1, 1),
    (7, 'member4@taskmasterpro.test', '$2y$12$mmAWYNDHvBAOaKEaO3RrA.c3BGZXmSb4q2C2l.6sV4Hw0QBbtHvLC', 'Quinn Analyst', 'team_member', 1, 1),
    (8, 'member5@taskmasterpro.test', '$2y$12$mmAWYNDHvBAOaKEaO3RrA.c3BGZXmSb4q2C2l.6sV4Hw0QBbtHvLC', 'Parker QA', 'team_member', 1, 1);

INSERT INTO projects (project_id, project_name, description, start_date, end_date, category, priority, status, created_by, organization_id)
VALUES
    (1, 'Website Refresh', 'Modernize the marketing website and update branding.', '2024-01-10', '2024-03-15', 'design', 'high', 'active', 2, 1),
    (2, 'Mobile App Launch', 'Launch the first version of the mobile companion app.', '2024-02-01', '2024-05-30', 'development', 'critical', 'active', 3, 1),
    (3, 'Customer Research', 'Collect feedback from enterprise clients.', '2024-01-20', '2024-04-10', 'research', 'medium', 'planning', 2, 1);

INSERT INTO project_team (project_id, user_id, role, added_by)
VALUES
    (1, 2, 'manager', 1),
    (1, 4, 'member', 2),
    (1, 6, 'member', 2),
    (2, 3, 'manager', 1),
    (2, 5, 'member', 3),
    (2, 8, 'member', 3),
    (3, 2, 'manager', 1),
    (3, 7, 'member', 2);

INSERT INTO tasks (task_id, project_id, title, description, assigned_to, created_by, priority, status, due_date, estimated_hours)
VALUES
    (1, 1, 'Create new homepage layout', 'Design the new homepage sections and CTA.', 6, 2, 'high', 'in_progress', '2024-02-10', 12),
    (2, 1, 'Update brand guidelines', 'Refresh typography and color palette.', 4, 2, 'medium', 'todo', '2024-02-20', 8),
    (3, 2, 'Implement authentication flow', 'Build login and register screens for mobile app.', 5, 3, 'critical', 'in_progress', '2024-03-05', 20),
    (4, 2, 'QA test push notifications', 'Validate notification triggers and copy.', 8, 3, 'high', 'todo', '2024-03-12', 10),
    (5, 3, 'Interview enterprise customers', 'Conduct 5 interviews and summarize insights.', 7, 2, 'medium', 'todo', '2024-03-01', 15);
