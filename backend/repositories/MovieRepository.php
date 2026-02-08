<?php
class MovieRepository {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM movies ORDER BY title DESC");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Movie');
    }
    
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Movie');
        return $stmt->fetch();
    }
    
    public function create(Movie $movie) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO movies (title, description, duration, release_year, genre, director) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->execute([
            $movie->title,
            $movie->description,
            $movie->duration,
            $movie->release_year,
            $movie->genre,
            $movie->director
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    public function update(Movie $movie) {        
        $stmt = $this->pdo->prepare(
            "UPDATE movies 
             SET title = ?, description = ?, duration = ?, release_year = ?, genre = ?, director = ?
             WHERE id = ?"
        );
        
        return $stmt->execute([
            $movie->title,
            $movie->description,
            $movie->duration,
            $movie->release_year,
            $movie->genre,
            $movie->director,
            $movie->id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM movies WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
