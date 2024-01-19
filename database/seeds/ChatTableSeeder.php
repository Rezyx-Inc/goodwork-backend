<?php

use Illuminate\Database\Seeder;
use App\Models\Chat;

class ChatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employerId = 'GWU000002';
        $workerIds = ['GWU000003', 'GWU000004'];

        $possibleMessages = [
            'Hello, how are you?',
            'I am fine, thank you. How about you?',
            'I am good too. What are you working on?',
            'I am working on a new project.',
            'That sounds interesting. Can you tell me more about it?',
            'Sure, it is a web application for managing tasks.',
            'Sounds great. How can I help?',
            'I need help with the database design.',
            'I can definitely help with that. Let\'s start.',
            'Thank you. I appreciate your help.',
            'Let\'s discuss the requirements first.',
            'Sure, I have prepared a list of requirements.',
            'Great, please share it with me.',
            'I have sent it to your email.',
            'I have received it. Let\'s start with the first requirement.',
            'Okay, the first requirement is user authentication.',
            'We can use Laravel\'s built-in authentication for that.',
            'That sounds good. What about the database?',
            'We can use MySQL for the database.',
            'Okay, let\'s start with the database design.'
        ];

        $generateRandomConversation = function($numMessages) use ($possibleMessages) {
            $messages = [];
            $possibleSenders = ['EMPLOYER', 'WORKER'];
            
            for ($i = 0; $i < $numMessages; $i++) {
                $randomSender = $i % 2 == 0 ? 'EMPLOYER' : 'WORKER';
                $randomMessage = $possibleMessages[array_rand($possibleMessages)];
                $randomTime =  now()->toDateTimeString();

                $messages[] = [
                    'id' => uniqid(),
                    'sender' => $randomSender,
                    'type' => 'text',
                    'content' => $randomMessage,
                    'time' => $randomTime,
                ];
            }

            return $messages;
        };

        foreach ($workerIds as $workerId) {
            $messages = $generateRandomConversation(20);

            Chat::create([
                'lastMessage' => now()->toDateTimeString(),
                'isActive' => true,
                'employerId' => $employerId,
                'workerId' => $workerId,
                'messages' => $messages,
            ]);
        }
    }
}