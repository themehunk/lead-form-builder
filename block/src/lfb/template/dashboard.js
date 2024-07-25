import { createRoot, render, createElement,useState,useEffect } from '@wordpress/element';
import { Flex, FlexBlock, FlexItem,Button } from '@wordpress/components';
import { Icon, arrowRight,chevronLeftSmall, color } from '@wordpress/icons';
import { Logo, Upgrade } from '../aisb';
import { __ } from '@wordpress/i18n';
import Header from './header';
import BuilderIcon from './buildericon';
import AiBuilder from './aibuilder';

export default function dashboard(props){

  const [activeTab, setActiveTab] = useState('si');

  const [ builder, setBuilder ] = useState(null);

  const builderHide = (builder_rs) => {
    setBuilder(builder_rs);
  }

  const handleClick = (active,url='')=>{
    setActiveTab(active);
    window.location.href = AISB.baseurl+'wp-admin/admin.php?page=vayu-blocks'+url;
  }

  const btnStyle= { color:"#fff", 
  background:"var(--aisb-bg-color)" 
}

return(<div className='aisb-dashboard'> 
<div className='aisb-header-wrap'>
<Flex className="aisb-dashboard-header" direction={[
    'column',
    'row'
  ]}>
<FlexBlock className='aisb-logo'>
<Logo/>
<h2>{__('VAYU BLOCKS', 'vayu-blocks')} </h2>
</FlexBlock>

<FlexBlock className='th-menu-wrap'>
<div className={`th-menu-item ${activeTab==='w' && 'active'}`} onClick={()=>handleClick('w','')}>{__('Welcome', 'vayu-blocks')}</div>
<div className={`th-menu-item ${activeTab==='s' && 'active'}`} onClick={()=>handleClick('s','&path=blocks')}>{__('Settings', 'vayu-blocks')}</div>
<div className={`th-menu-item ${activeTab==='si' && 'active'}`}>{__('Vayu Sites', 'vayu-blocks')}</div>
</FlexBlock>

<FlexItem>
<div className="header-text">

                <Upgrade styles = { btnStyle } />
                <Icon icon={<svg className = "dashboard-link" fill="#fff"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M19.5 4.5h-7V6h4.44l-5.97 5.97 1.06 1.06L18 7.06v4.44h1.5v-7Zm-13 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3H17v3a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h3V5.5h-3Z"></path></svg>} />
            </div>
</FlexItem>
</Flex>
</div>

<div class="th-content">
  <div class="th-conatiner">


  <div className='aisb-left-content'>
  <h3>    Build Your Website</h3>

  <a href={window.location.href+'&template=step'}>
  <h2 className='create-website'>
  <img src={AISB.pluginpath+'admin/assets/svg/create-site.svg'} />
    Create My Website
  </h2></a>
  </div>
  <div className='aisb-left-content'>
<iframe width="560" height="315" src="https://www.youtube.com/embed/buuvC61uD9s" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
</div>
</div>
</div>


</div>);
}