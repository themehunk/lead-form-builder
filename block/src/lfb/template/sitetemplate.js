import { useState,useEffect  } from '@wordpress/element';
import {SkeletonSingle, SkeletonTemplate} from './skeleton-loader';
import { useSelector, useDispatch } from 'react-redux';
import {addTrueFalse} from '../actions';

export default function SiteTemplate(props) {
  const [loaded, setLoaded] = useState(false);
  const [imgstyle, setImgstyle] = useState({display:'none'});

  const loader = useSelector((state)=>state.trueFalse);
  const jsonData = useSelector((state)=>state.templateData);
  const dispatch = useDispatch();

const imageHandel = (template)=> {
  dispatch(addTrueFalse(false));
  // Get the modal
  var parsedData = JSON.parse(template);
  props.datatemp(parsedData);


  
 // var captionText = document.getElementById("sidebarModel");
  var captionIframe = document.getElementById("iframeModel");
  var modal = document.getElementById("myModal");
  modal.style.display = "block";

// captionText.innerHTML = template;

// modalImg.src = parsedData.demo_url+'/?hide';

// // Get the <span> element that closes the modal
// var span = document.getElementsByClassName("close")[0];
// // When the user clicks on <span> (x), close the modal
//   span.onclick = function() { 
//     modal.style.display = "none";
//     document.body.style.overflow = "auto";
//     modalImg.src = '';
//   }
}

const tmplStyleHide = {
  display: 'none',
};

const tmplStyleShow = {
  display: 'block',
};

const customizer = ['topstore','top-store-pro','big-store','openshop-pro','jotshop','open-mart','m-shop','shopline-pro','amaz-store','almaira','gogo','novelpro','oneline','portfolioline','featured'];
const elementor = ['th-shop-mania','elementor','royal-shop'];

const gutenbergtmpl = ['vayu-x','th-shop-mania','blockline','blockline-pro','blur','blur-pro','gutenberg'];

const builderHandel = (builder) => {
  if (customizer.includes(builder)) {
    return 'customizer';
  } else if(elementor.includes(builder)){
    return 'elementor';
  } else if(gutenbergtmpl.includes(builder)){
    return 'gutenberg';
 }

}

const imgload = () =>{

  if(loaded===false){
 setLoaded(true);
 setImgstyle({display:'block'});
  }

}


useEffect(() => {
  setTimeout(() => {
    dispatch(addTrueFalse(true));
  }, 500); // 10000 milliseconds = 10 seconds
  
}, []);

return (
        <div class="asib-main-tmpl">
{/* {loader==false && <SkeletonTemplate/>} */}

{<div class="image-container">
      { jsonData.sort((a, b) => a.name > b.name ? -1 : 1).map((template,index) => {    
  return (<div key={index} className={`column builder-${builderHandel(template.builder_theme)}` }  onClick={() => imageHandel(JSON.stringify(template))} >
<div className='asib-tmpl-column'><div class="aisb-tmpl-item" data-id={template.id}>
</div>
{loader!==true && <SkeletonSingle/> }

<img id="myImg" demourl={template.demo_url} src={template.thumb} alt={template.title}  onLoad={imgload} style={imgstyle}/>

  <div className='asib-tmpl-footer'>
    <h3>{template.title}</h3>
    {template.free_paid =="paid" && <a className='aisb-pro' data-pro='Premium' ><img src={`${AISB.pluginpath}admin/assets/images/pro.png`}  alt="Premium" /></a>}
  </div>
</div>
</div>);
})}
  </div>}
</div>
    );
  }