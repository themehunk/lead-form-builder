import { useState  } from '@wordpress/element';

import { Button, Flex, FlexBlock, FlexItem, CheckboxControl  } from '@wordpress/components';
import { Icon, arrowRight } from '@wordpress/icons';
import TextField from '@mui/material/TextField';
import { useSelector, useDispatch } from 'react-redux';
import {stepOne,stepThree} from '../actions';
import { Upgrade } from '../aisb';

export default function buildWibsite(props){
    const pageStep = useSelector((state)=>state.stepLoad);
    const dispatch = useDispatch();
    const [checkboxes, setCheckboxes] = useState({
        delete: true,
        themes: true,
        plugins: true,
        content:true,
      });

      const handleCheckboxChange = (checkboxName) => {
        setCheckboxes({
          ...checkboxes,
          [checkboxName]: !checkboxes[checkboxName],
        });
      };


return( <div className='aisb-site-build-wrap'>

            <div className='aisb-site-build'>
                <Flex className='header'>

                    <FlexItem>
                    <div class="aisb-back">
                    <div class="sb-column-close"><Button onClick={()=>dispatch(stepOne(true))}variant="primary" className="close"><Icon size ={10} icon={ <svg fill="#000000" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><title/><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg> } />Back</Button></div>
                </div>
                    </FlexItem>

                    <FlexItem>  
                        <div className="header-text"><Upgrade/></div>
                 </FlexItem>
                </Flex>
            </div>
            <div className='aisb-site-main'>
                <div className='aisb-site-form'>
                    <h2> Lets Build Your AI Website</h2>
                    <h4 className='aisb-site-sub-heading'>Before be Continue Pease Confirm These Options - </h4>
                    <div className='aisb-site-options'>
                    

                        <div class="group"><input type="checkbox" id="delete" checked={checkboxes.delete} onChange={() => handleCheckboxChange('delete')}/> <label for="delete">Delete Pervious Import Demo </label></div>
                        <div class="group"><input type="checkbox" id="themes" checked={checkboxes.themes} /> <label for="themes">Install & Activate Theme </label></div>
                        <div class="group"><input type="checkbox" id="plugins" checked={checkboxes.plugins} /> <label for="plugins">Install Required Plugins </label></div>
                        <div class="group"><input type="checkbox" id="content" checked={checkboxes.content} onChange={() => handleCheckboxChange('content')}/> <label for="content">Install Demo content </label></div>

                
                    </div>

                    {/* <div>
                    <h4>About You</h4>
                    <div className='aisb-site-about'>
                    <Flex>
                    <FlexItem>
                    <TextField size="small" id="outlined-basic" label="Name" variant="outlined"  />
                    </FlexItem>
                    <FlexBlock>
                    <TextField size="small" id="outlined-basic" label="Email" variant="outlined"  />
                    </FlexBlock>
                    </Flex>
                    <div className='aisb-iagree'><CheckboxControl label="I agree to receive Newsletter & Updates" /> </div>
                    </div>
                    </div> */}


                    <div className='btn-center'><span className='aisb-install-btn' onClick={()=>dispatch(stepThree(true))}>Build My Website<b><Icon size ={22} icon={ arrowRight } /></b></span></div>
                </div>
            </div>

  </div>
  );
}