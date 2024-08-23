-- Create the database
CREATE DATABASE IF NOT EXISTS 2024nathanangeles;
USE 2024nathanangeles;

-- Create the Venues table
CREATE TABLE IF NOT EXISTS Venues (
  venue_id INT NOT NULL AUTO_INCREMENT,
  venue_name VARCHAR(100) NOT NULL,
  PRIMARY KEY (venue_id)
);

-- Create the Teams table
CREATE TABLE IF NOT EXISTS Teams (
  team_id INT NOT NULL AUTO_INCREMENT,
  team_name VARCHAR(100) NOT NULL,
  home_city VARCHAR(100) NOT NULL,
  venue_id INT NOT NULL,
  PRIMARY KEY (team_id),
  FOREIGN KEY (venue_id) REFERENCES Venues(venue_id)
);

-- Create the Matches table with sets
CREATE TABLE IF NOT EXISTS Matches (
  match_id INT NOT NULL AUTO_INCREMENT,
  match_date DATE NOT NULL,
  home_team_id INT NOT NULL,
  away_team_id INT NOT NULL,
  home_team_score INT DEFAULT 0,
  away_team_score INT DEFAULT 0,
  set_1_home_score INT DEFAULT 0,
  set_1_away_score INT DEFAULT 0,
  set_2_home_score INT DEFAULT 0,
  set_2_away_score INT DEFAULT 0,
  set_3_home_score INT DEFAULT 0,
  set_3_away_score INT DEFAULT 0,
  PRIMARY KEY (match_id),
  FOREIGN KEY (home_team_id) REFERENCES Teams(team_id),
  FOREIGN KEY (away_team_id) REFERENCES Teams(team_id)
);