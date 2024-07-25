import { useState,useEffect } from '@wordpress/element';
import { Button, Flex, FlexBlock, FlexItem } from '@wordpress/components';
import axios from 'axios';
import wpPlugins from '../assets/json/plugins.json';
import animationLoading  from '../assets/lottie/loading';
import animationProgress  from '../assets/lottie/progress';
import ImportAPI from './importapi';
import { getQueryArg } from '@wordpress/url';
import { useSelector, useDispatch } from 'react-redux';
import {tmplLodaing} from '../actions';
import Lottie from 'react-lottie';
import { Logo, Upgrade } from '../aisb';

function getThemeData(type){

  let hunkCompanion = 'hunk-companion';
  let vayBlocks = '';


const themeList = [ { 

gutenberg:[
  {
  type:'plugin', template: 'free', name: 'th-shop-mania',free:hunkCompanion,paid:'th-shop-mania-pro',builder:'gutenberg'
  }
],
vayu:[
  { 
    type:'theme', template: 'free', name: 'vayu-x',free:vayBlocks,paid:'vayu-x', builder:'gutenberg'
  },
]
}
];

switch(type){
  case "th-shop-mania" : return themeList[0].shopmania[0];
  case "vayu-x" : return themeList[0].vayu[0];
  default: return  themeList[0].vayu[0];
}

}


export default function installStart(props){
  const [ apiUrl, setApiUrl ] = useState(null);
  const dispatch = useDispatch();
  const lodaingMsg = useSelector((state)=>state.tmplInstall);

  // get theme name
  const getThemeName = () => {
    return getQueryArg( props.templateData.api_url, 'theme' );
  }

  const getPluginName = (type='') =>{
    const thmeType = getThemeData(props.templateData.builder_theme);

    if(type==='free'){
      
      return props.templateData.free_paid=='free'?thmeType.free:thmeType.paid;

    }else{
      return thmeType.type;
    }
  }

  const getBuilderName = (type='') =>{
    const thmeType = getThemeData(props.templateData.builder_theme);
    return thmeType.builder;
  }

  // plugin and theme install
        const process = async () =>{

          const params =  {
            templateType: props.templateData.free_paid,
            plugin: props.templateData.plugin,
            allPlugins:wpPlugins,
            builder:props.templateData.builder_theme,
            themeSlug:getThemeName(),
            proThemePlugin:getPluginName('free'),
            tmplFreePro:getPluginName()
          }      
            try {
                await axios.post(AISB.baseurl+'wp-json/ai/v1/ai-site-builder', {
                    params: {
                      templateType: props.templateData.free_paid,
                      plugin: props.templateData.plugin,
                      allPlugins:wpPlugins,
                      builder:props.templateData.builder_theme,
                      themeSlug:getThemeName(),
                      proThemePlugin:getPluginName('free'),
                      tmplFreePro:getPluginName()
                    }
                  })
                  .then(function (response) {
                    dispatch(tmplLodaing('Importing Server Data..'));
                    // console.log(response.data);
                    setApiUrl(props.templateData.api_url);
                  })
                  .catch(function (error) {
                    console.log(error);
                  })
                  .finally(function () {
                    // always executed
                  });

            } catch (error) {
                console.error('Error fetching data:', error);
              }
        }


        useEffect(() => {
          dispatch(tmplLodaing('Getting Started...'));
          process();       
        }, []); // 👈️ empty dependencies array

        const defaultLoading = {
          loop: true,
          autoplay: true, 
          animationData: animationLoading,
          rendererSettings: {
            preserveAspectRatio: 'xMidYMid slice'
          }
        };
        
        const defaultProgress = {
          loop: true,
          autoplay: true, 
          animationData: animationProgress,
          rendererSettings: {
            preserveAspectRatio: 'xMidYMid slice'
          }
        };

return(<div className='aisb-site-build-wrap'>
  
  <div className='aisb-site-build'>
                <Flex className='header'>
                    <FlexItem>
                    <Logo/>
                    </FlexItem>

                    <FlexItem> <div className="header-text">
            <Upgrade/>
            </div></FlexItem>
                </Flex>
            </div>

            <div className='aisb-site-main'>
                <div className='aisb-site-form'>
                    <h2> Getting Your Site Ready ...</h2>
 
              <Lottie options={defaultLoading} height={300} />
              <Lottie options={defaultProgress} width={300} />

              {  apiUrl===null && <span className='loading-msg'>{lodaingMsg}</span>}
              {  apiUrl && <ImportAPI apiurl = {apiUrl}  />}


                </div>
            </div>
  </div>);


}