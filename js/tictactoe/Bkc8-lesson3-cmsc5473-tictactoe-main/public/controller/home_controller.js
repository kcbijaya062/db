import { game } from "../view/home_page.js";
import { GameState } from "../model/tictactoe_game.js";
import { updateWindow } from "../view/home_page.js";
import { addTicTacToeGameRecord } from "./firestore_controller.js";
import { currentUser } from "./firebase_auth.js";
import { marking } from "../model/tictactoe_game.js";
import { Dev } from "../model/constant.js";

export async function onClickBoardButton(e) {
    //console.log(e.target.value);
    //console.log(e.currentTarget.value);
    const pos = parseInt(e.currentTarget.value[3]);
    game.play(pos);
    game.setWinner();
    if (game.winner != null) {

        game.gameState = GameState.DONE;
        await savePlayerRecord();
    } else {
        game.changeTurn();
    }
    //game.setWinner();
    updateWindow();
}
async function savePlayerRecord() {
    const email = currentUser.email;
    const moves = game.moves;
    let winner = game.winner;
    if (winner == marking.U) {
        winner = 'Draw';
    }
    const timestamp = Date.now();
    const playRecord = { email, moves, winner, timestamp };
   const div = document.createElement('div');
   div.classList.add('text-white','bg-primary');
   div.textContent ='saving to firestore....';
   document.getElementById('message').appendChild(div);




    try {
        await addTicTacToeGameRecord(playRecord);

    } catch (e) {
        if (Dev) console.log('failed to save play record', e);
        alert(`failed to save: ${JSON.stringify(e)}`);

    }
    div.remove();


}

export function onClickNewGame(e) {
    // console.log(e.target.id);
    game.reset();
    game.gameState = GameState.PLAYING;
    updateWindow();
}