# 🎯 Archery Tracker

A minimalist Laravel application for tracking archery scores in your office with a fun leaderboard system!

## Getting Started

To set up this project from scratch, follow these steps:

```bash
# 1. Clone the repository
git clone https://github.com/hugomazeas/arc_tracker.git
cd arc_tracker

# 2. Install PHP dependencies
composer install

# 3. Copy the environment file and generate application key
cp .env.example .env
php artisan key:generate

# 4. Create the database and run migrations
touch database/database.sqlite
php artisan migrate

# 5. Start the development server
php artisan serve --host=0.0.0.0 --port=8000
```

Then visit: `http://localhost:8000`

## Features

- **Interactive Visual Target**: Click on an SVG target to record arrow positions with exact X,Y coordinates
- **Automatic Scoring**: Scores calculated based on distance from center (6-10 points)
- **Bonus System**: Extensible bonus calculation with three built-in bonuses:
  - **All Same Score** (+3 pts): Hit the same score 4 times
  - **Final 10** (+5 pts): Hit 10 points with the last arrow
  - **Quadrant Master** (+8 pts): All 4 arrows in the same quadrant
- **Weekly Leaderboard**: Track rankings by week with detailed statistics
- **Simple Player Selection**: Dropdown list, no authentication needed

## Tech Stack

- Laravel 12
- SQLite database
- Vanilla JavaScript frontend
- SVG-based target interface

## Quick Start (If Already Installed)

If the application is already set up in your directory:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Then visit: `http://localhost:8000`

## Database Structure

### Players Table
- `id`: Primary key
- `name`: Player name (unique)
- `created_at`, `updated_at`

### Games Table
- `id`: Primary key
- `player_id`: Foreign key to players
- `arrow_data`: JSON array with arrow coordinates and scores
- `base_score`: Sum of arrow points
- `bonus_score`: Sum of bonus points
- `total_score`: base_score + bonus_score
- `created_at`: Game timestamp (used for weekly rankings)

## API Endpoints

### Players
- `GET /api/players` - List all players
- `POST /api/players` - Create a new player
- `GET /api/players/{id}` - Get player details
- `PUT /api/players/{id}` - Update player
- `DELETE /api/players/{id}` - Delete player

### Games
- `GET /api/games` - List games (supports filters: player_id, start_date, end_date)
- `POST /api/games` - Submit a new game
- `GET /api/games/{id}` - Get game details
- `DELETE /api/games/{id}` - Delete a game

### Leaderboard
- `GET /api/leaderboard` - Get leaderboard (supports start_date, end_date filters)
- `GET /api/leaderboard/weekly` - Get weekly leaderboard (supports year, week parameters)

### Bonuses
- `GET /api/bonuses` - List all available bonuses

## Adding New Bonuses

To add a new bonus, create a class that extends the abstract `Bonus` class:

```php
<?php

namespace App\Services\Bonuses;

class YourNewBonus extends Bonus
{
    public function check(array $arrows): bool
    {
        // Your logic to check if bonus applies
        return true;
    }

    public function getPoints(): int
    {
        return 10; // Bonus points
    }

    public function getName(): string
    {
        return 'Bonus Name';
    }

    public function getDescription(): string
    {
        return 'Description of when this bonus is earned';
    }
}
```

Then register it in `app/Services/BonusCalculationService.php`:

```php
public function __construct()
{
    $this->bonuses = [
        new SameScoreFourTimesBonus(),
        new LastArrowTenBonus(),
        new AllArrowsSameQuadrantBonus(),
        new YourNewBonus(), // Add here
    ];
}
```

## Adding Players

You can add players via the API:

```bash
curl -X POST http://localhost:8000/api/players \
  -H "Content-Type: application/json" \
  -d '{"name": "John Doe"}'
```

Or update the `database/seeders/PlayerSeeder.php` and run:

```bash
php artisan db:seed --class=PlayerSeeder
```

## Target Scoring Configuration

The target scoring is configured in `app/Services/TargetScoringService.php`:

```php
private const RING_10 = 50;  // Innermost ring (10 points)
private const RING_9 = 100;  // 9 points
private const RING_8 = 150;  // 8 points
private const RING_7 = 200;  // 7 points
private const RING_6 = 250;  // Outermost ring (6 points)
```

These values match the SVG target in the frontend for consistency.

## Deployment to Raspberry Pi

1. Install PHP 8.2+ and Composer on your Raspberry Pi
2. Clone or copy this directory to your Pi
3. Run `composer install --no-dev --optimize-autoloader`
4. Set up a systemd service to auto-start the application:

```bash
sudo nano /etc/systemd/system/archery-tracker.service
```

```ini
[Unit]
Description=Archery Tracker
After=network.target

[Service]
Type=simple
User=pi
WorkingDirectory=/path/to/arc_tracker
ExecStart=/usr/bin/php artisan serve --host=0.0.0.0 --port=8000
Restart=always

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable archery-tracker
sudo systemctl start archery-tracker
```

## Browser Compatibility

The app works best on modern browsers with good SVG support. Tested on:
- Chrome/Edge
- Firefox
- Safari

Perfect for a Raspberry Pi connected to a TV or monitor with a keyboard/mouse!

## License

Open source - modify as needed for your office!
