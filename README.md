# HiddenCMS Gamification

Gamification bundle for HiddenCMS. It provides four modules and their widgets:

- Teams and rosters
- Matches and scores
- Awards and rankings
- Tournaments

The package intentionally contains no WARDOGS RCON connection in its first release. A future integration can attach external player and server identifiers to the shared domain without exposing credentials to the browser.

## Included features

- Team CRUD with ordered rosters, roles, captains and optional Steam IDs.
- Match CRUD with linked or free-form opponents, schedule, status, score, stage, stream and tournament.
- Award CRUD with ranking, date, team and tournament associations.
- Tournament CRUD with formats, lifecycle statuses, participants, seeds and groups.
- Public index/detail routes and menu entries for every module.
- Configurable widgets for team rosters, upcoming matches/results, awards and tournaments.
- English source strings and bundled French translations.

Install the package through Composer, then run the regular HiddenCMS addon synchronization and migrations. All four modules share the `gamification_*` tables created by the package migration.

## Requirements

- PHP 8.1 or newer
- HiddenCMS Core 0.9 or 1.x

## Release status

The current public release is `0.0.2`. WARDOGS integration remains intentionally out of scope for this version.
