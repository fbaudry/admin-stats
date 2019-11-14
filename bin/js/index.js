const fs = require('fs');

const leaguesHelper = require('./vendor/leagues');
const classementsHelper = require('./vendor/classements');
const gameWithoutNulRankingHelper = require('./vendor/game_without_nul');
const lastgamesHelper = require('./vendor/last_games');
const nextGamesHelper = require('./vendor/next_games');
const teamsHelper = require('./vendor/teams');

const start = async () => {
    let dir = 'public/json/leagues';
    if (!fs.existsSync(dir)){
        fs.mkdirSync(dir);
    }

    let leagues = await leaguesHelper.start();
    for(let index = 0; index < leagues.length; ++index) {
        const league = leagues[index];
        await classementsHelper.start(league);
        await gameWithoutNulRankingHelper.start(league);
        await lastgamesHelper.start(league);
        await nextGamesHelper.start(league);
        await teamsHelper.start(league);
    }
};

start();



