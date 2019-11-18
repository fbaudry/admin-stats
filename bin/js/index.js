const fs = require('fs');

const leaguesHelper = require('./vendor/leagues');
const classementsHelper = require('./vendor/classements');
const gameWithoutNulRankingHelper = require('./vendor/game_without_nul');
const lastgamesHelper = require('./vendor/last_games');
const nextGamesHelper = require('./vendor/next_games');
const resultsGamesHelper = require('./vendor/results');
const teamsHelper = require('./vendor/teams');
const archivesHelper = require('./vendor/archives');

const start = async () => {
    let dir = 'public/json/leagues';
    if (!fs.existsSync(dir)){
        fs.mkdirSync(dir, {recursive: true});
    }

    let leagues = await leaguesHelper.start();
    leagues.map(league => fetch_leagues(league));
};

const fetch_leagues = async (league) => {
    await classementsHelper.start(league);
    await gameWithoutNulRankingHelper.start(league);
    await lastgamesHelper.start(league);
    await nextGamesHelper.start(league);
    await teamsHelper.start(league);
    await resultsGamesHelper.start(league);
    await archivesHelper.start(league);
};

start();


