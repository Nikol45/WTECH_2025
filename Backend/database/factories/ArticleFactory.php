<?php

namespace Database\Factories;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = \App\Models\User::where('is_admin', true)->inRandomOrder()->value('id');

        $titlesAndTexts = [
            'Ako správne podsadiť vajcia' => 'Prinášame overený spôsob, ako zabezpečiť úspešné liahnutie s pomocou náhradnej sliepky.',
            'Bataty v našej záhradke' => 'Pestovanie sladkých zemiakov môže byť jednoduché aj v našich podmienkach. Tu je náš postup.',
            'Domáce milkshaky z čerstvého mlieka' => 'Rýchly a chutný recept z mlieka od našich kravičiek.',
            'Uhorky, ktoré nechcú rásť' => 'Pozrieme sa na najčastejšie dôvody, prečo vaše uhorky stagnujú.',
            'Pestovanie rajčín od A po Z' => 'Od výsevu po zber — takto pestujeme zdravé a chutné paradajky.',
            'Výber ideálnej zemiakovej odrody' => 'Ako si vybrať správny zemiak podľa pôdy, chuti a sezóny.',
            'Kozy a ich každodenné prekvapenia' => 'Každý deň s kozami je jedinečný. Pozri sa, čo všetko nás dokážu naučiť.',
            'Náš recept na domáci jogurt' => 'Bez prídavných látok a úplne po domácky — chutný a zdravý jogurt z farmy.',
            'Záchrana dedkovho viniča' => 'Starý vinič potreboval pomoc. Ukážeme ti, ako sme ho zachránili pred zánikom.',
            'Kedy a ako sadiť cibuľu' => 'Najvhodnejší čas na výsadbu cibule a čo pri tom nezabudnúť.',
            'Ako udržať šťastné sliepky' => 'Pohoda sliepok sa rovná kvalitné vajcia. Tu je niekoľko našich tipov.',
            'Včelárenie v májových dňoch' => 'Na jar to vo včelíne vrie. Prečítaj si, čo všetko robíme práve teraz.',
            'Správny rez ovocných stromov' => 'Bez rezu to nejde. Tu je náš návod na zdravé ovocné stromy.',
            'Náš bio mak z vlastného poľa' => 'Pestujeme mak bez chémie a radi sa s tebou podelíme o skúsenosti.',
            'Udiareň postavená svojpomocne' => 'Nepotrebujete firmu – stačí pár dosiek, chuť a tento návod.',
            'Bylinková záhradka bez starostí' => 'Ako si udržať voňavé a zdravé bylinky po celý rok.',
            'Zdravé ovocné čipsy pre deti' => 'Domáce a chrumkavé – deti ich milujú, rodičia schvaľujú.',
            'Jednoduchá závlaha z dažďovky' => 'Zber dažďovej vody ušetrí peniaze aj rastlinám pomôže.',
            'Domáce klobásy krok za krokom' => 'Tradičný recept našej rodiny – poctivé a voňavé klobásky.',
            'Čo robíme na farme v apríl' => 'Jar je plná práce. Pozri sa, čo všetko sa deje v tomto mesiaci.'
        ];

        $title = $this->faker->randomElement(array_keys($titlesAndTexts));
        $text = $titlesAndTexts[$title];

        return [
            'user_id' => $userId,
            'title' => $title,
            'text' => $text
        ];
    }

}
