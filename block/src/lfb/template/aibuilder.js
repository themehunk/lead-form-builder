import { useState  } from '@wordpress/element';
import { FormFileUpload } from '@wordpress/components';

import SkeletonLoader from './skeleton-loader';
import SidebarCategory from './sidebarcategory';
import SiteTemplate from './sitetemplate';
import IframeTemplate from './IframeTemplate';
import { useSelector, useDispatch } from 'react-redux';
import {stepOne,stepTwo,stepThree} from '../actions';
import InstallStart from './installStart';
import BuildWibsite from './buildwebsite';
import Success from './success';
import { Icon, chevronDown,chevronUp } from '@wordpress/icons';

export default function AiBuilder(props) {
  const myState = useSelector((state)=>state.templateSelect);
  const pageStep = useSelector((state)=>state.stepLoad);
  const dispatch = useDispatch();
  const [ templateData, setTemplateData ] = useState(null);
  const [ iframeurl, setIframeUrl ] = useState(false);
  const [ loader, setLoaderl ] = useState(true);
  const [ nextBtn, setNextBtn ] = useState(true);
  const [ stepThree, setStepThree ] = useState(false);
  const [ iframeDisplay, setIframeDisplay ] = useState({display:"block"});
  const [isHidden, setIsHidden] = useState(false);

  const handeldata =  (jdata)=> {
    try {
      dispatch(stepOne(true));
    document.body.style.overflow = "hidden";
    setIframeDisplay({display:"none"});
   // document.getElementById("iframetmpl").style.display = "none"; 
     setTemplateData(jdata);
    
    setLoaderl(true);
    setIframeUrl(jdata.demo_url+'/?hide&theme');
    setTimeout(function() {
      frameload();

    }, 3000);


  } catch (error) {
    console.error('Error fetching data:', error);
  }

      }
     
      const frameload =  ()=> {
      //  sendPostMessage();

         setTimeout(function() {
          setLoaderl(false);
          setIframeDisplay({display:"block"});
        }, 2000);
       
       }

      const installHandel = ()=>{
        dispatch(stepTwo(true));
        setNextBtn(false);
       setStepThree(true);
       }


      const handelDownFotter = () =>{
        setIsHidden(!isHidden);
      }


       const sendPostMessage = () => {
        const iframe = document.getElementById('iframetmpl');      
          const iframeCallData = {class:'custom-logo',src:'https://zitademo.wpzita.com/physiotherapy/wp-content/uploads/sites/105/2023/06/physiotherapy-32.png',
          call: 'satrtUploadData',
      };
          console.log(iframeCallData);
      
              iframe.contentWindow.postMessage(iframeCallData, '*');

      }


    return (<>
        <div class="aisb-container-main-tmpl">
        <SiteTemplate  datatemp={(jdata)=>handeldata(jdata)} builderHide = {props.builder}/>
      </div>
      <div id="myModal" class="aisb-model modal">

          {pageStep.createWebsite &&  <BuildWibsite />}

         {pageStep.install && <InstallStart templateData={templateData} />}

         {  pageStep.success && <Success />}


        {  /*** demo show and next button click */}
      {pageStep.iframe &&  <div class="aisb-container-demo-tmpl theme-install-overlay-stop wp-full-overlay-stop expanded-stop">
              <div class={`left-column wp-full-overlay-sidebar-stop ${isHidden ? 'tiphide' : ''}`} id="sidebarModel">
              <b onClick={handelDownFotter} className='footer-tip'><Icon size ={22} icon={ isHidden ? chevronUp : chevronDown } /></b>
              {templateData !== null && <IframeTemplate templateData={templateData} installHandel = {()=>installHandel()}/>}

              </div>
              <div class="right-column-demo modal-content wp-full-overlay-main" id="iframeModel">
              {loader && <SkeletonLoader/>}
              
                <iframe onLoad={frameload} id="iframetmpl" src={iframeurl} height="100%" width="100%" frameborder="0" style={iframeDisplay}></iframe>
                {/* {<FormFileUpload
            accept="image/*"
            onChange={ ( event ) => console.log( event.currentTarget.files ) }
        >
            Upload image
        </FormFileUpload>} */}
              </div>
          </div>}


      </div> 
      </>);
  }