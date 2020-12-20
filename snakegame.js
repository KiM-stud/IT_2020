const canvas = document.getElementById("snakegame");
const scoreElement = document.getElementById("score");
canvas.width = 400;     //window of the game, it's need to be a square
canvas.height = 400;
const context = canvas.getContext("2d");
const size = 25;    //size of the square, it's need to be a devider of width and height
const xEnd = canvas.width    //end of each axis
const yEnd = canvas.height;
let directionLock = false;  //variable that informs if player pressed a key
score=0;
const t=1;
context.lineWidth=t;
const snake = [{x: 0, y: 0}];
const apple = {};
let direction = 39;
let speed = 125;    //speed of snake

//randomizing function
function random (min, max) 
{
  return Math.random() * (max - min) + min;
}
//function for setting an apple
function setApple() 
{
  apple.x = Math.round(random(size, canvas.width - size) / size) * size;
  apple.y = Math.round(random(size, canvas.height - size) / size) * size;
  for (let k=0; k<snake.length; k++) 
  {
    if (snake[k].x === apple.x && snake[k].y === apple.y)
      setApple();
  }
}
//that function draws snake, apple and grid on the board
function draw() 
{
  context.clearRect(0, 0, canvas.width, canvas.height);
  for(let k=0;k<=24;k++)        //24==600/25
  {
      drawLine1(k*size, 0);
      drawLine2(0, k*size);
  }
  drawBorder(apple.x, apple.y, size, size);
  context.fillStyle = "red";
  context.fillRect(apple.x, apple.y, size, size);  
  for (let i = 0; i < snake.length; i ++)
  {
    const s = snake[i];
    drawBorder(s.x, s.y, size, size);
    context.fillStyle = "darkgreen";
    context.fillRect(s.x, s.y, size, size);
  }

  window.requestAnimationFrame(draw);
}
//main function in the game
function loop() 
{
  for (let i=snake.length-1; i>=0; i--) 
  {
    //cheking if snake ate an apple
    if (i === 0 && snake[i].x === apple.x && snake[i].y === apple.y) 
    {
        score++;
        snake.push({});
        setApple();
        scoreElement.innerHTML=score;
    }

    const s = snake[i];
    if (i == 0) 
    {
    //cheking direction in which snake's moving
      switch(direction) 
      {
        case 37:
          if (s.x < size) 
            s.x = xEnd-size;
          else 
          s.x -= size;
        break;
        case 38:
          if (s.y < size) 
            s.y = yEnd-size;
          else 
            s.y -= size;
        break;
        case 39:
          if (s.x >= xEnd-size) 
            s.x = 0;
          else 
          s.x += size;
        break;
        case 40:
          if (s.y >= yEnd-size) 
            s.y = 0;
          else 
            s.y += size;
        break;
      }
      //checking if snake hit itself
      for (let j=1; j<snake.length; j++) 
      {
        if (snake[0].x===snake[j].x && snake[0].y===snake[j].y) 
        {
            window.alert("Twój Wynik: "+score);
            window.location.reload();
        }
      }

    } 
    //moving a snake
    else 
    {
      snake[i].x = snake[i-1].x;
      snake[i].y = snake[i-1].y;
    }
  }
  window.setTimeout(loop, speed);
  directionLock = false;
}
//function that detects what key player pressed
function checkkey(e) 
{
  if (!directionLock) 
  {
    directionLock = true;
    const newDirection = e.keyCode;
    //checking if player pressed an arrow
    if(newDirection>36 && newDirection<41)
    {
      //cheking if player wants to go to opposite direction
      if (direction ==37 && newDirection != 39) 
          direction = newDirection;
      if (direction ==38 && newDirection !=40) 
          direction = newDirection;
      if (direction ==40 && newDirection !=38) 
          direction = newDirection;
      if (direction ==39 && newDirection !=37) 
          direction = newDirection;
    }
  }
}
//that functions draw border of the square and grid on board
function drawBorder(x, y, W, H)
{
    context.fillStyle="black";
    context.fillRect(x-t, y-t, W+2*t, H+2*t);
}

function drawLine1(x, y)
{
    context.beginPath();
    context.moveTo(x,y);
    context.lineTo(x,y+canvas.height);
    context.stroke();
}
function drawLine2(x, y)
{
    context.beginPath();
    context.moveTo(x,y);
    context.lineTo(x+canvas.width,y);
    context.stroke();
}


setApple();
window.addEventListener("keydown", checkkey);
window.setTimeout(loop, speed);
window.requestAnimationFrame(draw);