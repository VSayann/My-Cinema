<?php
class Room {
    public $id;
    public $title;
    public $description;
    public $capacity;
    public $type;
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