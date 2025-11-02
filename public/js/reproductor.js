// Playlist and player logic with cover images from caratulas/
const playBtn = document.getElementById('playBtn');
const progress = document.getElementById('progress');
const time = document.querySelector('.progress-row .time');
const prevBtn = document.querySelector('.nav-arrow.left');
const nextBtn = document.querySelector('.nav-arrow.right');

let playing = false;
let currentIndex = 0; // index in the playlist array
let seconds = 0;

const playlist = [
  { title: 'Trench', img: 'caratulas/trench.jpg', duration: 220 },
  { title: 'Nectar', img: 'caratulas/nectar.jpg', duration: 210 },
  { title: 'Blurryface', img: 'caratulas/blurryface.jpg', duration: 205 },
  { title: 'Ballads', img: 'caratulas/ballads.jpg', duration: 230 }
];

function idx(offset){
  return (currentIndex + offset + playlist.length) % playlist.length;
}

function updateCards(){
  // select card elements (they always remain in DOM)
  const prevCard = document.querySelector('.card.prev');
  const curCard = document.querySelector('.card.current');
  const nextCard = document.querySelector('.card.next');

  const prevData = playlist[idx(-1)];
  const curData = playlist[idx(0)];
  const nextData = playlist[idx(1)];

  // update backgrounds and titles
  prevCard.querySelector('.cover-inner').style.backgroundImage = `url('${prevData.img}')`;
  prevCard.querySelector('.song-name').textContent = prevData.title;

  curCard.querySelector('.cover-inner').style.backgroundImage = `url('${curData.img}')`;
  curCard.querySelector('.song-name').textContent = curData.title;

  nextCard.querySelector('.cover-inner').style.backgroundImage = `url('${nextData.img}')`;
  nextCard.querySelector('.song-name').textContent = nextData.title;

  // update duration display
  document.querySelector('.progress-row .total').textContent = `${Math.floor(curData.duration/60)}:${(curData.duration%60).toString().padStart(2,'0')}`;
}

function setPositions(){
  // remove position classes then set them according to structure
  const all = document.querySelectorAll('.card');
  all.forEach(c=> { c.classList.remove('prev','current','next'); });
  // We rely on the order of cards in the DOM: prev, current, next
  document.querySelector('.cards .card:nth-child(1)').classList.add('prev');
  document.querySelector('.cards .card:nth-child(2)').classList.add('current');
  document.querySelector('.cards .card:nth-child(3)').classList.add('next');
}

function changeTrack(direction){
  // update currentIndex
  if(direction === 'next') currentIndex = (currentIndex + 1) % playlist.length;
  else currentIndex = (currentIndex - 1 + playlist.length) % playlist.length;

  // Instead of moving DOM nodes, rotate the data by shifting content
  // We'll swap the cover images/texts with a smooth class transition
  updateCards();
  resetProgress();
}

prevBtn.addEventListener('click', ()=> changeTrack('prev'));
nextBtn.addEventListener('click', ()=> changeTrack('next'));

playBtn.addEventListener('click', ()=>{
  playing = !playing;
  playBtn.textContent = playing ? '⏸' : '▶';
});

function resetProgress(){ seconds = 0; progress.value = 0; time.textContent = '0:00'; }

setInterval(()=>{
  if(!playing) return;
  const curDur = playlist[currentIndex].duration;
  seconds = Math.min(curDur, seconds + 1);
  const mins = Math.floor(seconds/60);
  const secs = seconds%60;
  time.textContent = `${mins}:${secs.toString().padStart(2,'0')}`;
  const pct = Math.floor((seconds/curDur)*100);
  progress.value = pct;
  if(seconds>=curDur){ changeTrack('next'); }
},1000);

progress.addEventListener('input', ()=>{
  const pct = Number(progress.value);
  const curDur = playlist[currentIndex].duration;
  seconds = Math.floor((pct/100)*curDur);
  const mins = Math.floor(seconds/60);
  const secs = seconds%60;
  time.textContent = `${mins}:${secs.toString().padStart(2,'0')}`;
});

// initialize
setPositions();
updateCards();
