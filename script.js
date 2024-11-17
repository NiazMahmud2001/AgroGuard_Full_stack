import { FontLoader } from 'FontLoader';
import { OrbitControls } from "OrbitControls";
import { GLTFLoader } from 'gltfLoader';
import { TextGeometry } from 'textGeo';
import * as THREE from "three";
import gsap from "gsap";



console.log(GLTFLoader);
console.log(THREE);
console.log(gsap);

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
var posArray = new Float32Array(partCount*5);

for(let i=0 ; i<partCount*3 ; i++){
   posArray[i] = (Math.random()-0.5)*5;
}
partGeo.setAttribute("position" , new THREE.BufferAttribute(posArray,3));


var material5 = new THREE.MeshStandardMaterial({
   // "MeshStandardMaterial" and "MeshPhogMaterial" cast or receive shadows
   color: 0xf0f0f0,
   side: THREE.DoubleSide,
   roughness: 0.1, 
   metalness: 0, 
 });

 var material6 = new THREE.PointsMaterial({
   size: 0.0001 ,
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
//camera.position.z = 2;

const gltfLoader = new GLTFLoader();
gltfLoader.load(
  "./model/untitled.gltf",
  (gltf) => {
      console.log(gltf);
      gltf.scene.castShadow = true;
      gltf.scene.receiveShadow = true;

    var clk = new THREE.Clock();
      const tick1 = () => {
         const tt = clk.getElapsedTime();
         gltf.scene.children[1].children[2].children[0].rotation.y += 0.01;
         gltf.scene.children[1].children[3].children[0].rotation.y += 0.01;
 
         window.requestAnimationFrame(tick1);
      }
      tick1();
      gltf.scene.position.y += 0.05;
      gltf.scene.children[0].intensity = 100; 
      gltf.scene.children[2].intensity = 20 ; 
      scene.add(gltf.scene);
  },
  () => {
    console.log("success");
  },
  () => {
    console.log("error");
  }
);
//
//adding three js fonts==========================================
const loader = new FontLoader();
loader.load( 'https://threejs.org/examples/fonts/helvetiker_regular.typeface.json', function ( font ) {

	const geometry = new TextGeometry( 'AgroGuard', {
		font: font,
		size: 0.07,
		height: 0.01,
		curveSegments: 10,
	} );
   var text_pos= new THREE.Mesh(geometry , material5);
   text_pos.position.x -= 0.28;
   text_pos.position.y -= 0.18;
   scene.add(text_pos)

} );

//scene.add(new THREE.AxesHelper(5));
//scene.add(new THREE.GridHelper(50, 50));

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
 spotLight.castShadow = true; // default false
 
 //Set up shadow properties for the light:::::------
 spotLight.shadow.mapSize.width = 2000; // default
 spotLight.shadow.mapSize.height = 2000; // default
 spotLight.shadow.camera.near = 0.5; // default(value::-- 0 to 1)
 spotLight.shadow.camera.far = 500; // default
 
 const spotLightHelper = new THREE.SpotLightHelper(spotLight);
 //scene.add( spotLightHelper );
 
 scene.add(spotLight);
 //==================== end spotLight     =========================================

var orbitControls = new OrbitControls(camera, renderer.domElement);
orbitControls.enableDamping = true;
orbitControls.dampingFactor = 0.05;
orbitControls.screenSpacePanning = false;
orbitControls.minDistance = 0;
orbitControls.maxDistance = 500;
orbitControls.maxPolarAngle = Math.PI / 1;
orbitControls.enableZoom  = false;

var time = Date.now();
const clock = new THREE.Clock();
const tick = () => {
  const currentTime = Date.now();
  const deltaTime = currentTime - time; 
  time = currentTime;

  const elapsedTime = clock.getElapsedTime();
  
  //console.log(elapsedTime)

  orbitControls.update();

  //console.log(screen.width)


  renderer.render(scene, camera);
  window.requestAnimationFrame(tick);
};

tick();
