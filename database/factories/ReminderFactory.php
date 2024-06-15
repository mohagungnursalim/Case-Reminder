<?php

namespace Database\Factories;

use App\Models\Reminder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReminderFactory extends Factory
{
    protected $model = Reminder::class;

    public function definition()
    {
        return [
            'nama_kasus' => $this->faker->sentence(3),
            'pesan' => $this->faker->paragraph,
            'tanggal_waktu' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'nama_jaksa' => json_encode($this->faker->words(3, false)), // Contoh dengan 3 nama acak
            'nomor_jaksa' => json_encode([$this->faker->phoneNumber, $this->faker->phoneNumber, $this->faker->phoneNumber]), // Contoh dengan 3 nomor acak
            'nama_saksi' => json_encode($this->faker->words(3, false)), // Contoh dengan 3 nama acak
            'is_sent' => $this->faker->boolean,
        ];
    }
}
