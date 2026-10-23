<?php

namespace HiddenCMS\Gamification\Migrations;

use HB\HiddenCMS\Addons\Migration;

class CreateGamificationTables implements Migration
{
	public function up($db)
	{
		$db->execute_checked('CREATE TABLE IF NOT EXISTS gamification_teams (
			team_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			slug VARCHAR(150) NOT NULL,
			title VARCHAR(150) NOT NULL,
			description TEXT NOT NULL,
			logo_id INT UNSIGNED NULL,
			banner_id INT UNSIGNED NULL,
			color VARCHAR(7) NOT NULL DEFAULT "#126b58",
			published TINYINT NOT NULL DEFAULT 1,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			UNIQUE KEY slug (slug),
			KEY published (published)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

		$db->execute_checked('CREATE TABLE IF NOT EXISTS gamification_team_members (
			member_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			team_id INT UNSIGNED NOT NULL,
			user_id INT UNSIGNED NULL,
			display_name VARCHAR(150) NOT NULL,
			role VARCHAR(100) NOT NULL,
			steam_id VARCHAR(32) NULL,
			captain TINYINT NOT NULL DEFAULT 0,
			sort_order INT NOT NULL DEFAULT 0,
			published TINYINT NOT NULL DEFAULT 1,
			KEY team_id (team_id),
			KEY user_id (user_id),
			UNIQUE KEY team_steam (team_id, steam_id),
			CONSTRAINT gamification_member_team FOREIGN KEY (team_id)
				REFERENCES gamification_teams(team_id) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

		$db->execute_checked('CREATE TABLE IF NOT EXISTS gamification_tournaments (
			tournament_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			slug VARCHAR(150) NOT NULL,
			title VARCHAR(150) NOT NULL,
			description TEXT NOT NULL,
			format VARCHAR(30) NOT NULL DEFAULT "single_elimination",
			status VARCHAR(20) NOT NULL DEFAULT "draft",
			starts_at DATETIME NULL,
			ends_at DATETIME NULL,
			location VARCHAR(255) NOT NULL,
			image_id INT UNSIGNED NULL,
			published TINYINT NOT NULL DEFAULT 1,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			UNIQUE KEY slug (slug),
			KEY status (status),
			KEY starts_at (starts_at)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

		$db->execute_checked('CREATE TABLE IF NOT EXISTS gamification_tournament_participants (
			participant_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			tournament_id INT UNSIGNED NOT NULL,
			team_id INT UNSIGNED NULL,
			name VARCHAR(150) NOT NULL,
			seed_number INT UNSIGNED NULL,
			group_name VARCHAR(50) NOT NULL,
			sort_order INT NOT NULL DEFAULT 0,
			KEY tournament_id (tournament_id),
			KEY team_id (team_id),
			CONSTRAINT gamification_participant_tournament FOREIGN KEY (tournament_id)
				REFERENCES gamification_tournaments(tournament_id) ON DELETE CASCADE,
			CONSTRAINT gamification_participant_team FOREIGN KEY (team_id)
				REFERENCES gamification_teams(team_id) ON DELETE SET NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

		$db->execute_checked('CREATE TABLE IF NOT EXISTS gamification_matches (
			match_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			tournament_id INT UNSIGNED NULL,
			title VARCHAR(180) NOT NULL,
			home_team_id INT UNSIGNED NULL,
			home_name VARCHAR(150) NOT NULL,
			away_team_id INT UNSIGNED NULL,
			away_name VARCHAR(150) NOT NULL,
			starts_at DATETIME NULL,
			status VARCHAR(20) NOT NULL DEFAULT "scheduled",
			best_of INT UNSIGNED NOT NULL DEFAULT 1,
			score_home INT UNSIGNED NULL,
			score_away INT UNSIGNED NULL,
			stage VARCHAR(100) NOT NULL,
			round_number INT UNSIGNED NULL,
			location VARCHAR(255) NOT NULL,
			stream_url VARCHAR(500) NOT NULL,
			description TEXT NOT NULL,
			published TINYINT NOT NULL DEFAULT 1,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			KEY tournament_id (tournament_id),
			KEY home_team_id (home_team_id),
			KEY away_team_id (away_team_id),
			KEY starts_at (starts_at),
			KEY status (status),
			CONSTRAINT gamification_match_tournament FOREIGN KEY (tournament_id)
				REFERENCES gamification_tournaments(tournament_id) ON DELETE SET NULL,
			CONSTRAINT gamification_match_home_team FOREIGN KEY (home_team_id)
				REFERENCES gamification_teams(team_id) ON DELETE SET NULL,
			CONSTRAINT gamification_match_away_team FOREIGN KEY (away_team_id)
				REFERENCES gamification_teams(team_id) ON DELETE SET NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

		$db->execute_checked('CREATE TABLE IF NOT EXISTS gamification_match_rounds (
			round_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			match_id INT UNSIGNED NOT NULL,
			title VARCHAR(100) NOT NULL,
			map_name VARCHAR(100) NOT NULL,
			score_home INT UNSIGNED NOT NULL DEFAULT 0,
			score_away INT UNSIGNED NOT NULL DEFAULT 0,
			sort_order INT NOT NULL DEFAULT 0,
			KEY match_id (match_id),
			CONSTRAINT gamification_round_match FOREIGN KEY (match_id)
				REFERENCES gamification_matches(match_id) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

		$db->execute_checked('CREATE TABLE IF NOT EXISTS gamification_awards (
			award_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			team_id INT UNSIGNED NULL,
			tournament_id INT UNSIGNED NULL,
			title VARCHAR(180) NOT NULL,
			awarded_at DATE NOT NULL,
			ranking INT UNSIGNED NOT NULL DEFAULT 1,
			participants INT UNSIGNED NULL,
			location VARCHAR(255) NOT NULL,
			description TEXT NOT NULL,
			image_id INT UNSIGNED NULL,
			published TINYINT NOT NULL DEFAULT 1,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			KEY team_id (team_id),
			KEY tournament_id (tournament_id),
			KEY awarded_at (awarded_at),
			CONSTRAINT gamification_award_team FOREIGN KEY (team_id)
				REFERENCES gamification_teams(team_id) ON DELETE SET NULL,
			CONSTRAINT gamification_award_tournament FOREIGN KEY (tournament_id)
				REFERENCES gamification_tournaments(tournament_id) ON DELETE SET NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
	}

	public function down($db)
	{
		$db->execute_checked('DROP TABLE IF EXISTS gamification_awards');
		$db->execute_checked('DROP TABLE IF EXISTS gamification_match_rounds');
		$db->execute_checked('DROP TABLE IF EXISTS gamification_matches');
		$db->execute_checked('DROP TABLE IF EXISTS gamification_tournament_participants');
		$db->execute_checked('DROP TABLE IF EXISTS gamification_tournaments');
		$db->execute_checked('DROP TABLE IF EXISTS gamification_team_members');
		$db->execute_checked('DROP TABLE IF EXISTS gamification_teams');
	}
}
