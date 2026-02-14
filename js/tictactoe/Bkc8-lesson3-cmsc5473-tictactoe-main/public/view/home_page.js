import { currentUser } from "../controller/firebase_auth.js";

import { root } from "./elements.js";
import { protectedView } from "./protected_view.js";
import { onClickBoardButton, onClickNewGame } from "../controller/home_controller.js";
import { TicTactoeGame } from "../model/tictactoe_game.js";
import { GameState } from "../model/tictactoe_game.js";
import { marking } from "../model/tictactoe_game.js";

export const images = {
  X: '/images/ximage.jpg',
  O: '/images/oimage.jpg',
  U: '/images/failcasse.jpg',
};
export let game = new TicTactoeGame();

export async function homePageView() {
  if (!currentUser) {
    root.innerHTML = await protectedView();
    return;

  }
  //root.innerHTML ='<h1>Home page </h1>';

  const response = await fetch('/view/templates/home_page_template.html',
    { cache: 'no-store' });
  const divWrapper = document.createElement('div');
  divWrapper.innerHTML = await response.text();
  divWrapper.classList.add('m-4', 'p-4');

  const buttons = divWrapper.querySelectorAll('table button');
  buttons.forEach(b => b.onclick = onClickBoardButton);

  const newGameButton = divWrapper.querySelector('#button-new-game');
  newGameButton.onclick = onClickNewGame;
  
  root.innerHTML = '';
  root.appendChild(divWrapper);
  
  updateWindow();
}

export function updateWindow() {
  const buttons = document.querySelectorAll('table button');
  //const newGameButton = document.getElementById('button-new-game');
  const newGameButton = document.querySelector('#button-new-game');
  const turnImg = document.querySelector('#turn');
  turnImg.src = images[game.turn];
  switch (game.gameState) {
    case GameState.INIT:
      buttons.forEach(b => b.disabled = true);
      newGameButton.disabled = false;
      for (let i = 0; i < game.board.length; i++) {
        const img = buttons[i].firstElementChild;
        img.src = images[game.board[i]];
      }
      document.getElementById('message').innerHTML = 'Press New Game to Start';

      break;
    case GameState.PLAYING:
      document.getElementById('message').innerHTML = ` click on the board to move.<br>
      (# of moves = ${game.moves})`;
      newGameButton.disabled = true;
      for (let i = 0; i < game.board.length; i++) {
        const img = buttons[i].firstElementChild;
        img.src = images[game.board[i]];
        buttons[i].disabled = game.board[i] != marking.U;
      } break;
    case GameState.DONE:
      buttons.forEach(b => b.disabled = true);
      newGameButton.disabled = false;
      for (let i = 0; i < game.board.length; i++) {
        const img = buttons[i].firstElementChild;
        img.src = images[game.board[i]];

      }
      let winner = game.winner + ' has won';
      if (game.winner == marking.U) {
        winner = 'Draw!';
      }
      let message = `
                  Game over! ${winner}<br><br>
                  press "New Game" to play again`;
      document.getElementById('message').innerHTML = message;
      break;
  }
}