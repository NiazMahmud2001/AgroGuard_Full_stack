
import * as THREE from "three";
import gsap from "gsap";

console.log(THREE);


var canvas = document.querySelector("#c");

var renderer = new THREE.WebGLRenderer({
  alpha: true,
  antialias: true,
});

renderer.setClearColor("black");
renderer.setPixelRatio(window.devicePixelRatio);
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.shadowMap.enabled = true;
renderer.shadowMap.type = THREE.PCFSoftShadowMap;
//document.body.appendChild(renderer.domElement);
canvas.append(renderer.domElement);

window.addEventListener("resize", function () {
  renderer.setSize(window.innerWidth, window.innerHeight);
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix(); 
});

var scene = new THREE.Scene();
var group = new THREE.Group();

//partical model
var partGeo = new THREE.BufferGeometry;
var partCount = 5000 ; 
var posArray = new Float32Array(partCount*3);

for(let i=0 ; i<partCount*3 ; i++){
   posArray[i] = (Math.random()-0.5)*2;
}
partGeo.setAttribute("position" , new THREE.BufferAttribute(posArray,3));

 var material6 = new THREE.PointsMaterial({
   size: 0.001 ,
 })


var mesh_points = new THREE.Points(partGeo , material6);
scene.add( mesh_points)


var fov = 65;
var aspect = window.innerWidth / window.innerHeight;
var near = 0.1;
var far = 1000000;
var camera = new THREE.PerspectiveCamera(fov, aspect, near, far);
camera.position.set(0.2, 0.35, 0.5);
camera.lookAt(0,0,0);

//==================== start spotLight   =========================================
const spotLight = new THREE.SpotLight(
   new THREE.Color("rgb(255,255,255)"),
   4,
   10000
 );
 //new THREE.SpotLight(color,intensity,light travelling distance)
 spotLight.position.set(20, 15, 0);
 spotLight.rotation.z = Math.PI / 6;
 spotLight.shadow.camera.fov = 50;
 spotLight.castShadow = true; 
 
 //Set up shadow properties for the light:::::------
 spotLight.shadow.mapSize.width = 2000; 
 spotLight.shadow.mapSize.height = 2000; 
 spotLight.shadow.camera.near = 0.5; 
 spotLight.shadow.camera.far = 500; 
 
 const spotLightHelper = new THREE.SpotLightHelper(spotLight);
 //scene.add( spotLightHelper );
 
 scene.add(spotLight);
 //==================== end spotLight     =========================================


var time = Date.now();
const clock = new THREE.Clock();
const tick = () => {
  const currentTime = Date.now();
  const deltaTime = currentTime - time;
  time = currentTime;

  const elapsedTime = clock.getElapsedTime();
  
  //console.log(elapsedTime)

  //orbitControls.update();


  renderer.render(scene, camera);
  window.requestAnimationFrame(tick);
};

tick();
