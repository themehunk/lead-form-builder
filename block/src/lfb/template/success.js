import { useState,useEffect } from '@wordpress/element';
import { useSelector} from 'react-redux';
import { Button, Flex, FlexBlock, FlexItem } from '@wordpress/components';
import animationSuccess  from '../assets/lottie/success';
import { getQueryArg } from '@wordpress/url';
import { Icon, arrowRight,chevronLeftSmall } from '@wordpress/icons';
import Lottie from 'react-lottie';
import { HomeLink, Logo, Upgrade } from '../aisb';




export default function success(){

  const templType = useSelector((state)=>state.templateSelect);

  const [customizeUrl,setCustomizeUrl] = useState();

    const defaultOptions = {
        loop: false,
        autoplay: true, 
        animationData: animationSuccess,
        rendererSettings: {
          preserveAspectRatio: 'xMidYMid slice'
        }
      };

      
      useEffect(() => {
        getEditUrl();       
      }, []); // 👈️ empty dependencies array


const getEditUrl = async () =>{
  try {
    const dataToSend = { data: templType,type:'edit' }; // Customize the data to send
    const response = await fetch(AISB.ajaxurl, {
      method: 'POST',
      body: new URLSearchParams({
          action: 'vayu_blocks_sites_core', // Specify the WordPress AJAX action
          data: JSON.stringify(dataToSend), // Convert the data to JSON and send it
      }),
  })

  .then(response => response.json())
      .then(data => {
          // Handle the AJAX respons            
          setCustomizeUrl(data.data);          
          // Perform any further actions with the response
      })
      .catch(error => {
          // Handle errors
          console.error('Error in AJAX request:', error);
      });

  } catch (error) {
      console.error('Error fetching data:', error);
    }
}

      

      const handleClick= () =>{
        // window.location.replace(AISB.baseurl);
   
         window.open(AISB.baseurl, '_blank');
   
       }

       const handleCustomize= () =>{
        // window.location.replace(AISB.baseurl);
   
         window.open(customizeUrl, '_blank');
   
       }


return(<div className='aisb-site-build-wrap'>
  
            <div className='aisb-site-build'>
              <Flex>
                  <FlexItem>
                  <Logo/>
                  </FlexItem>
                  <FlexItem>
                  <div className="header-text">
            <Upgrade/>
            <HomeLink/>
            </div></FlexItem>
              </Flex>
          </div>

          <div className='aisb-site-main'>
              <div className='aisb-site-form'>
              <h1> Congratulation</h1>

            <Lottie className='lottie-success' options={defaultOptions} width={230} />

            <h2>Your webiste is now ready. </h2>
            <Flex className='success-btn'>
                  <FlexItem>
                  <div className='btn-center'> <span className='aisb-install-btn' onClick={ handleCustomize }> <b><Icon
       icon={
        <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><rect x="0" fill="none" width="20" height="20"/>
        <g><path d="M14.48 11.06L7.41 3.99l1.5-1.5c.5-.56 2.3-.47 3.51.32 1.21.8 1.43 1.28 2.91 2.1 1.18.64 2.45 1.26 4.45.85zm-.71.71L6.7 4.7 4.93 6.47c-.39.39-.39 1.02 0 1.41l1.06 1.06c.39.39.39 1.03 0 1.42-.6.6-1.43 1.11-2.21 1.69-.35.26-.7.53-1.01.84C1.43 14.23.4 16.08 1.4 17.07c.99 1 2.84-.03 4.18-1.36.31-.31.58-.66.85-1.02.57-.78 1.08-1.61 1.69-2.21.39-.39 1.02-.39 1.41 0l1.06 1.06c.39.39 1.02.39 1.41 0z"/></g>
        </svg>
        }
   /></b>Customize </span></div>
                  </FlexItem>

                  <FlexItem>
                  <div className='btn-center'> <span className='aisb-border-btn' onClick={ handleClick }><b><Icon
       icon={ 
        <svg width="24px" height="24px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
<title>eye</title>
<path d="M0 16q0.064 0.128 0.16 0.352t0.48 0.928 0.832 1.344 1.248 1.536 1.664 1.696 2.144 1.568 2.624 1.344 3.136 0.896 3.712 0.352 3.712-0.352 3.168-0.928 2.592-1.312 2.144-1.6 1.664-1.632 1.248-1.6 0.832-1.312 0.48-0.928l0.16-0.352q-0.032-0.128-0.16-0.352t-0.48-0.896-0.832-1.344-1.248-1.568-1.664-1.664-2.144-1.568-2.624-1.344-3.136-0.896-3.712-0.352-3.712 0.352-3.168 0.896-2.592 1.344-2.144 1.568-1.664 1.664-1.248 1.568-0.832 1.344-0.48 0.928zM10.016 16q0-2.464 1.728-4.224t4.256-1.76 4.256 1.76 1.76 4.224-1.76 4.256-4.256 1.76-4.256-1.76-1.728-4.256zM12 16q0 1.664 1.184 2.848t2.816 1.152 2.816-1.152 1.184-2.848-1.184-2.816-2.816-1.184-2.816 1.184l2.816 2.816h-4z"></path>
</svg>
        }
   /></b>View Webiste</span></div>
                  </FlexItem>
              </Flex>
              </div>
          </div>
</div>)

}