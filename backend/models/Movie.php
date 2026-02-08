<?php
class Movie {
    public $id;
    public $title;
    public $description;
    public $duration;
    public $release_year;
    public $genre;
    public $director;
    public $created_at;
    public $updated_at;
    
    public function __construct($data = []) {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }
}
