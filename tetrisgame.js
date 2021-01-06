const canvas = document.getElementById("tetrisgame");
const context = canvas.getContext("2d");
const canvas2 = document.getElementById("tetrisnext");
const context2 = canvas2.getContext("2d");
const scoreElement = document.getElementById("score");
const size = 30;
const row = 20;
const column = 10;
const row2 = 4;
const column2 = 4;
const t = "wheat";
const start_speed = 300;
let score = 0;
let tetris = 0;
let speed = start_speed;

function uploadScore(score) {
    var request = new XMLHttpRequest();
    request.open("GET", "addscore.php?pkt=" + score +"&nr=2");
    request.send();
  }

function drawSquare(x, y, color) {
    context.fillStyle = color;
    context.fillRect(x * size, y * size, size, size);
    context.strokeStyle = "black";
    context.strokeRect(x * size, y * size, size, size);
}
let board = [];
let board2 = [];
for (r = 0; r < row; r++) {
    board[r] = [];
    for (c = 0; c < column; c++) {
        board[r][c] = t;
    }
}

for (r = 0; r < row2; r++) {
    board2[r] = [];
    for (c = 0; c < column2; c++) {
        board2[r][c] = t;
    }
}

function drawBoard() {
    for (r = 0; r < row; r++) {
        for (c = 0; c < column; c++) {
            drawSquare(c, r, board[r][c]);
        }
    }
}

function drawNextSquare(x, y, color) {
    context2.fillStyle = color;
    context2.fillRect(x * size, y * size, size, size);
    context2.strokeStyle = "black";
    context2.strokeRect(x * size, y * size, size, size);
}
function drawNextBoard() {
    for (r = 0; r < row2; r++) {
        for (c = 0; c < column2; c++) {
            drawNextSquare(c, r, board2[r][c]);
        }
    }
}

drawBoard();
drawNextBoard();

const Pieces =
    [
        [I],
        [J],
        [L],
        [O],
        [S],
        [Z],
        [T]
    ];

const Colors=
[
    ["deeppink"],
    ["blueviolet"],
    ["indianred"],
    ["crimson"],
    ["darkred"],
    ["darkorange"],
    ["orangered"],
    ["gold"],
    ["yellowgreen"],
    ["forestgreen"],
    ["darkturquoise"],
    ["cadetblue"],
    ["deepskyblue"],
    ["midnightblue"],
    ["saddlebrown"]
];

function random() {
    let r = Math.floor(Math.random() * Pieces.length);
    let q = Math.floor(Math.random() * Colors.length);
    return new Piece(Pieces[r][0], Colors[q][0]);
}

let p = random();
let next = random();

function Piece(figure, color) {
    this.figure = figure;
    this.color = color;
    this.N = 0;
    this.activeFigure = this.figure[this.N];
    //OG:
    this.x = 3;
    this.y = -2;

}
Piece.prototype.next = function () {
    this.fillnext(this.color);
}
Piece.prototype.unNext = function () {
    this.fillnext(t);
}
Piece.prototype.fillnext = function (color) {
    if (this.figure == I) {
        this.y = 1;
        this.x = 0;
    }
    else {
        if (this.figure == O) {
            this.y = 0;
            this.x = 0;
        }
        else {
            this.y = 1;
            this.x = 1;
        }
    }
    for (r = 0; r < this.activeFigure.length; r++) {
        for (c = 0; c < this.activeFigure.length; c++) {
            if (this.activeFigure[r][c]) {
                drawNextSquare(this.x + c, this.y + r, color);
            }
        }
    }
}
Piece.prototype.fill = function (color) {
    for (r = 0; r < this.activeFigure.length; r++) {
        for (c = 0; c < this.activeFigure.length; c++) {
            if (this.activeFigure[r][c]) {
                drawSquare(this.x + c, this.y + r, color);
            }
        }
    }
}

Piece.prototype.draw = function () {
    this.fill(this.color);
}

Piece.prototype.unDraw = function () {
    this.fill(t);
}

Piece.prototype.moveDown = function () {
    if (!this.collision(0, 1, this.activeFigure)) {
        this.unDraw();
        this.y++;
        this.draw();
    }
    else {
        this.lock();
        //p = random();
        next.unNext();
        p = next;
        p.x = 3;
        p.y = -2;
        next = random();
        next.next();
    }
}

Piece.prototype.moveRight = function () {
    if (!this.collision(1, 0, this.activeFigure)) {
        this.unDraw();
        this.x++;
        this.draw();
    }
}

Piece.prototype.moveLeft = function () {
    if (!this.collision(-1, 0, this.activeFigure)) {
        this.unDraw();
        this.x--;
        this.draw();
    }
}

Piece.prototype.rotate = function () {
    let next = this.figure[(this.N + 1) % this.figure.length];
    let kick = 0;
    if (this.collision(0, 0, next)) {
        if (this.x > column / 2) {
            kick = -1;
        }
        else {
            kick = 1;
        }
    }
    if (!this.collision(kick, 0, next)) {
        this.unDraw();
        this.x += kick;
        this.N = (this.N + 1) % this.figure.length;
        this.activeFigure = this.figure[this.N];
        this.draw();
    }
}

Piece.prototype.lock = function () {
    for (r = 0; r < this.activeFigure.length; r++) {
        for (c = 0; c < this.activeFigure.length; c++) {
            if (!this.activeFigure[r][c]) {
                continue;
            }
            if (this.y + r < 0) {
                gameOver = true;
                uploadScore(score);
                window.alert("Twój Wynik: " + score);
                window.location.reload();
            }
            board[this.y + r][this.x + c] = this.color;
        }
    }
    for (r = 0; r < row; r++) {
        let fullRow = true;
        for (c = 0; c < column; c++) {
            fullRow = fullRow && (board[r][c] != t);
        }
        if (fullRow) {
            for (y = r; y > 1; y--) {
                for (c = 0; c < column; c++) {
                    board[y][c] = board[y - 1][c];
                }
            }
            for (c = 0; c < column; c++) {
                board[0][c] = t;
            }
            tetris += 1;
        }
    }
    switch (tetris) {
        case 1:
            score += 1;
            break;
        case 2:
            score += 3;
            break;
        case 3:
            score += 6;
            break;
        case 4:
            score += 10;
            break;
    }
    if (tetris != 0) {
        if (start_speed - (score * 100) <= 300 || speed <= 300) {
            if (speed >= 250)
                speed = speed - 5;
            else
                speed = speed - 1;
        }
        else
            speed = start_speed - (score * 100);
        tetris = 0;
    }

    drawBoard();
    scoreElement.innerHTML = score;

}

Piece.prototype.collision = function (x, y, piece) {
    for (r = 0; r < piece.length; r++) {
        for (c = 0; c < piece.length; c++) {
            if (!piece[r][c]) {
                continue;
            }
            let newX = this.x + c + x;
            let newY = this.y + r + y;
            if (newX < 0 || newX >= column || newY >= row) {
                return true;
            }
            if (newY < 0) {
                continue;
            }
            if (board[newY][newX] != t) {
                return true;
            }
        }
    }
    return false;
}

document.addEventListener("keydown", keycheck);
function keycheck(e) {
    if (e.keyCode == 37) {
        p.moveLeft();
        //dropStart=Date.now();
    }
    else if (e.keyCode == 38) {
        p.rotate();
        //dropStart=Date.now();
    }
    else if (e.keyCode == 39) {
        p.moveRight();
        //dropStart=Date.now();
    }
    else if (e.keyCode == 40) {
        p.moveDown();
    }
}

let dropStart = Date.now();
let gameOver = false;
function drop() {
    let now = Date.now();
    let delta = now - dropStart;
    if (delta > speed) {
        //next.next();
        p.moveDown();
        dropStart = Date.now();
    }
    if (!gameOver) {
        requestAnimationFrame(drop);
    }
}
next.next();
drop();
