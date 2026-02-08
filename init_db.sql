USE my_cinema;

ALTER TABLE movies ADD UNIQUE KEY unique_title (title);

INSERT INTO movies (title, director, genre, duration, release_year, description) VALUES
('Scott Pilgrim VS. The World', 'Edgar Wright', 'Comedy', 113, 2010, 'Scott Pilgrim doit vaincre les 7 ex de sa nouvelle petite amie'),
('V For Vendetta', 'James McTeigue', 'Thriller', 132, 2005, 'Un combattant de la liberté masqué lutte contre un régime totalitaire'),
('Oldboy', 'Park Chan-wook', 'Action', 120, 2003, 'Un homme emprisonné pendant 15 ans cherche à se venger'),
('Bugonia', 'Yorgos Lanthimos', 'Thriller', 118, 2025, 'Un thriller psychologique moderne et captivant'),
('Last Night in Soho', 'Edgar Wright', 'Mystery', 117, 2021, 'Une jeune fille est mystérieusement transportée dans les années 60'),
('Get Out', 'Jordan Peele', 'Thriller', 104, 2017, 'Un jeune homme découvre un secret terrifiant chez les parents de sa petite amie'),
('Fast and Furious : Tokyo Drift', 'Justin Lin', 'Action', 104, 2006, 'Un adolescent découvre le monde du drift racing à Tokyo'),
('Sinners', 'Ryan Coogler', 'Thriller', 138, 2025, 'Un thriller intense et captivant'),
('Yes Man', 'Peyton Reed', 'Romance', 104, 2008, 'Un homme décide de dire oui à tout pendant un an'),
('Django Unchained', 'Quentin Tarantino', 'Drama', 165, 2012, 'Un esclave libéré part à la recherche de sa femme');

ALTER TABLE rooms ADD UNIQUE KEY unique_title (title);

INSERT INTO rooms (title, description, capacity, type) VALUES
('Salle 1 - Grande', 'Notre plus grande salle avec écran géant IMAX', 300, 'IMAX'),
('Salle 2 - Confort', 'Salle confort avec sièges inclinables VIP', 150, 'VIP'),
('Salle 3 - Standard', 'Salle standard pour projections classiques', 200, 'Standard'),
('Salle 4 - 3D', 'Salle équipée pour les films en 3D avec son surround', 180, '3D');

INSERT INTO screenings (movie_id, room_id, screening_date) VALUES

(1, 1, '2026-02-07 14:00:00'),
(1, 1, '2026-02-07 20:30:00'),

(2, 2, '2026-02-07 15:00:00'),
(2, 2, '2026-02-07 21:00:00'),

(3, 3, '2026-02-07 16:00:00'),
(3, 3, '2026-02-07 22:00:00'),

(4, 4, '2026-02-07 17:30:00'),
(4, 4, '2026-02-07 23:00:00');

INSERT INTO screenings (movie_id, room_id, screening_date) VALUES
(5, 1, '2026-02-08 14:30:00'),
(5, 1, '2026-02-08 19:00:00'),

(6, 2, '2026-02-08 16:00:00'),
(6, 2, '2026-02-08 20:30:00'),

(7, 3, '2026-02-08 15:00:00'),
(7, 3, '2026-02-08 21:00:00'),

(8, 4, '2026-02-08 18:00:00'),
(8, 4, '2026-02-08 22:30:00');

INSERT INTO screenings (movie_id, room_id, screening_date) VALUES

(9, 1, '2026-02-09 11:00:00'),
(9, 1, '2026-02-09 16:00:00'),
(9, 1, '2026-02-09 21:00:00'),

(10, 2, '2026-02-09 13:00:00'),
(10, 2, '2026-02-09 19:00:00'),

(1, 3, '2026-02-09 14:00:00'),
(1, 3, '2026-02-09 20:00:00'),

(2, 4, '2026-02-09 15:30:00'),
(2, 4, '2026-02-09 22:00:00');

INSERT INTO screenings (movie_id, room_id, screening_date) VALUES

(3, 1, '2026-02-10 14:00:00'),
(3, 1, '2026-02-10 19:30:00'),

(4, 2, '2026-02-10 15:30:00'),
(4, 2, '2026-02-10 21:00:00'),

(6, 3, '2026-02-10 16:00:00'),
(6, 3, '2026-02-10 22:00:00'),

(7, 4, '2026-02-10 17:00:00'),
(7, 4, '2026-02-10 23:00:00');

INSERT INTO screenings (movie_id, room_id, screening_date) VALUES

(8, 1, '2026-02-11 14:30:00'),
(8, 1, '2026-02-11 20:00:00'),

(9, 2, '2026-02-11 16:00:00'),
(9, 2, '2026-02-11 21:30:00'),

(10, 3, '2026-02-11 18:00:00'),

(5, 4, '2026-02-11 15:00:00'),
(5, 4, '2026-02-11 20:30:00');