<?php

namespace App\Constants;

class Finance
{
    public const CATEGORIES = [
        [
            'slug' => 'rent',
            'icon' => '🏠',
            'title' => 'Rent',
        ],
        [
            'slug' => 'utilities',
            'icon' => '💡',
            'title' => 'Utilities',
        ],
        [
            'slug' => 'groceries',
            'icon' => '🥕',
            'title' => 'Groceries',
        ],
        [
            'slug' => 'transportation',
            'icon' => '🚗',
            'title' => 'Transportation',
        ],
        [
            'slug' => 'healthcare',
            'icon' => '💊',
            'title' => 'Healthcare',
        ],
        [
            'slug' => 'insurance',
            'icon' => '🛡️',
            'title' => 'Insurance',
        ],
        [
            'slug' => 'entertainment',
            'icon' => '🎮',
            'title' => 'Entertainment',
        ],
        [
            'slug' => 'dining_out',
            'icon' => '🍽️',
            'title' => 'Dining Out',
        ],
        [
            'slug' => 'subscriptions',
            'icon' => '📅',
            'title' => 'Subscriptions',
        ],
        [
            'slug' => 'loans_and_debts',
            'icon' => '💳',
            'title' => 'Loans and Debts',
        ],
        [
            'slug' => 'savings',
            'icon' => '💰',
            'title' => 'Savings',
        ],
        [
            'slug' => 'education',
            'icon' => '🎓',
            'title' => 'Education',
        ],
        [
            'slug' => 'gifts',
            'icon' => '🎁',
            'title' => 'Gifts',
        ],
        [
            'slug' => 'charity',
            'icon' => '🤝',
            'title' => 'Charity',
        ],
        [
            'slug' => 'clothing',
            'icon' => '👗',
            'title' => 'Clothing',
        ],
        [
            'slug' => 'home_improvement',
            'icon' => '🔨',
            'title' => 'Home Improvement',
        ],
        [
            'slug' => 'hobbies',
            'icon' => '🎨',
            'title' => 'Hobbies',
        ],
        [
            'slug' => 'personal_care',
            'icon' => '💅',
            'title' => 'Personal Care',
        ],
        [
            'slug' => 'other',
            'icon' => '❓',
            'title' => 'Other',
        ],
    ];

    public const CURRENCIES = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'JPY' => '¥',
        'CAD' => '$',
        'AUD' => '$',
        'CHF' => 'CHF',
        'CNY' => '¥',
        'INR' => '₹',
        'KRW' => '₩',
        'BRL' => 'R$',
        'RUB' => '₽',
        'VND' => '₫'
    ];

    public const DURATIONS = [
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ];
}