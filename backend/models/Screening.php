<?php
class Screening {
    public $id;
    public $movie_id;
    public $room_id;
    public $screening_date;
    public $created_at;
    public $updated_at;
    public $movie_title;
    public $room_title;
    
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
