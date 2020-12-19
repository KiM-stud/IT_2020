const canvas = document.getElementById('tetrisgame');
const context = canvas.getContext('2d');
const size = 50;
const row=20;
const column=10;
const theme="wheat";


function drawSquare(x,y,color)
{
    context.fillStyle=color;
    context.fillRect(x*size,y*size,size,size);
    context.strokeStyle="black";
    context.strokeRect(x*size,y*size,size,size);
}
let board=[];
for(r=0;r<row;r++)
{
    board[r]=[];
    for(c=0;c<column;c++)
    {
        board[r][c]=theme;
    }
}
function drawBoard()
{
    for(r=0;r<row;r++)
    {
        for(c=0;c<column;c++)
        {
            drawSquare(c,r,board[r][c]);
        }
    }
}

drawBoard();

const Pieces=
[
    [I,"red"],
    [J,"orange"],
    [L,"yellow"],
    [O,"green"],
    [S,"blue"],
    [Z,"indigo"]
    [T,"violet"]
];

function randomPiece()
{
    let r=Math.floor(Math.random()*Pieces.lenght);
    return new Pieces(Pieces[r,0],Pieces[r,1]);
}

let p=randomPiece();

function Piece(tetromino, color)
{
    this.tetromino=tetromino;
    this.color=color;
    this.tetrominoN=0;
    this.activeTetromino=this.tetromino[this.tetrominoN];

    this.x=3;
    this.y=-2;
}

Piece.prototype.fill=function(color)
{
    for(r=0;r<this.activeTetromino.lenght;r++)
    {
        for(c=0;r<this.activeTetromino.lenght;c++)
        {
            if(this.activeTetromino[r][c])
            {
                drawSquare(this.x+c,this.y+r,color);
            }
        }
    }
}

Piece.prototype.draw=function()
{
    this.fill(this.color);
}

Piece.prototype.unDraw=function()
{
    this.fill(theme);
}

Piece.prototype.moveDown=function()
{
    if(this.collision(0,1,this.activeTetromino))
    {
        this.unDraw();
        this.y++;
        this.draw();
    }
    else
    {
        this.lock();
        p=randomPiece();
    }
}

Piece.prototype.moveRight=function()
{
    if(!this.collision(1,0,this.activeTetromino))
    {
        this.unDraw();
        this.x++;
        this.draw();
    }
}

Piece.prototype.moveLeft=function()
{
    if(!this.collision(-1,0,this.activeTetromino))
    {
        this.unDraw();
        this.x--;
        this.draw();
    }
}

Piece.prototype.rotate=function()
{
    
}

