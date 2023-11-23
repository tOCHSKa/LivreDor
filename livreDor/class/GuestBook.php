<?php
require_once 'Message.php';

class GuestBook {

    private $file;


    public function __construct(string $file) 
    
    {
        $directory = dirname($file);
        if(!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        if(!file_exists($file)) {
            touch($file);
        }
        $this->file = $file;
    }

    public function addMessage(Message $message):void {

        file_put_contents($this->file, $message->toJSON() . "\n", FILE_APPEND);

    }

    public function getMessages(): array {
        $content = trim(file_get_contents($this->file));
        $lines = explode("\n", $content);
        $messages = [];
    
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            
            // Vérifiez si les clés existent et ne sont pas null
            $username = isset($data['username']) ? $data['username'] : '';
            $message = isset($data['message']) ? $data['message'] : '';
            
            // Correction : Utilisez directement le timestamp pour créer un objet DateTime
            $date = new DateTime("@{$data['date']}");
            $date->setTimeZone(new DateTimeZone('Europe/Paris'));
            
            // Créez un nouvel objet Message en utilisant les valeurs récupérées
            $messages[] = new Message($username, $message, $date);
        }
    
        return array_reverse($messages);
    }
    
    

}