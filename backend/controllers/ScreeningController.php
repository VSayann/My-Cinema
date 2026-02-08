<?php
class ScreeningController {
    private $repository;
    
    public function __construct() {
        $this->repository = new ScreeningRepository();
    }
    
    public function list() {
        $screenings = $this->repository->getAll();
        $this->sendResponse(200, ['screenings' => $screenings]);
    }
    
    public function get($id) {
        $screening = $this->repository->getById($id);
        $this->sendResponse(200, ['screening' => $screening]);
    }
    
    public function create() {
        $data = $this->getRequestData();
        $screening = new Screening($data);
            
        $id = $this->repository->create($screening);
        $this->sendResponse(201, ['message' => 'Séance créée', 'id' => $id]);
    }
    
    public function update($id) {
        $data = $this->getRequestData();
        $data['id'] = $id;
        $screening = new Screening($data);
            
        $result = $this->repository->update($screening);
        $this->sendResponse(200, ['message' => 'Séance mise à jour']);
    }
    
    public function delete($id) {
        $result = $this->repository->delete($id);
        $this->sendResponse(200, ['message' => 'Séance supprimée']);
    }
    
    public function getByDate($date) {
        $screenings = $this->repository->getByDate($date);
        $this->sendResponse(200, ['screenings' => $screenings]);
    }
    
    public function getByMovie($movieId) {
        $screenings = $this->repository->getByMovie($movieId);
        $this->sendResponse(200, ['screenings' => $screenings]);
    }
    
    private function getRequestData() {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
    
    private function sendResponse($statusCode, $data) {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
