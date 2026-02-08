<?php
class RoomController {
    private $repository;
    
    public function __construct() {
        $this->repository = new RoomRepository();
    }
    
    public function list() {
            $rooms = $this->repository->getAll();
            $this->sendResponse(200, ['rooms' => $rooms]);
    }
    
    public function get($id) {
            $room = $this->repository->getById($id);
            $this->sendResponse(200, ['room' => $room]);
    }
    
    public function create() {
            $data = $this->getRequestData();
            $room = new Room($data);
            $id = $this->repository->create($room);
            $this->sendResponse(201, ['message' => 'Salle créée', 'id' => $id]);
    }
    
    public function update($id) {
            $data = $this->getRequestData();
            $data['id'] = $id;
            $room = new Room($data);
            $errors = $room->validate();
            $result = $this->repository->update($room);
            $this->sendResponse(200, ['message' => 'Salle mise à jour']);
    }
    
    public function delete($id) {
            $result = $this->repository->delete($id);
            $this->sendResponse(200, ['message' => 'Salle supprimée']);
    }
    
    private function getRequestData() {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
    
    private function sendResponse($statusCode, $data) {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
