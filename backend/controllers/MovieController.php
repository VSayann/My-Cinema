<?php
class MovieController {
    private $repository;
    
    public function __construct() {
        $this->repository = new MovieRepository();
    }
    
    public function list() {
            $movies = $this->repository->getAll();
            $this->sendResponse(200, ['movies' => $movies]);
    }
    
    public function get($id) {
            $movie = $this->repository->getById($id);
            $this->sendResponse(200, ['movie' => $movie]);
    }
    
    public function create() {
            $data = $this->getRequestData();
            $movie = new Movie($data);
            $id = $this->repository->create($movie);
            $this->sendResponse(201, ['message' => 'Film ajouté', 'id' => $id]);
    }
    
    public function update($id) {
            $data = $this->getRequestData();
            $data['id'] = $id;
            $movie = new Movie($data);
            
            $result = $this->repository->update($movie);
            if ($result) {
                $this->sendResponse(200, ['message' => 'Film mis à jour']);
            }
    }
    
    public function delete($id) {
            $result = $this->repository->delete($id);
            if ($result) {
                $this->sendResponse(200, ['message' => 'Film supprimé']);
            }
}
    
    private function getRequestData() {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
    
    private function sendResponse($statusCode, $data) {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
