import { Icon, styles } from '@wordpress/icons';
import { Button } from '@wordpress/components';

 function Upgrade(props) {
        const version = 'Version 1.0';
    return (<>{props.btn && <a href={AISB.upgrade} target='_blank'>
    <Button variant="primary" className='aisb-upgrade-btn' style={props.styles}>
    <Icon icon={<svg  viewBox="0 0 1024 1024" class="icon"  version="1.1"><path d="M57 438.312l109.536 488.72h697.336l109.536-488.72-259.176 156.816-187.856-333.088-205.352 333.088z" fill="#EC9312" /><path d="M629.048 211.888c0 58.912-47.752 106.656-106.672 106.656-58.92 0-106.664-47.744-106.664-106.656 0-58.976 47.744-106.656 106.664-106.656s106.672 47.688 106.672 106.656z" fill="#CB1B5B" /><path d="M522.376 105.232c-58.92 0-106.664 47.68-106.664 106.656 0 58.912 47.744 106.656 106.664 106.656V105.232z" fill="#E5226B" /><path d="M57 438.312l109.536 488.72h697.336z" fill="#F4A832" /><path d="M973.408 438.312l-109.536 488.72H166.536z" fill="#F4A832" /><path d="M166.536 927.032h697.336L515.2 715.832z" fill="#F5B617" /><path d="M1017.856 409.44a55.2 55.2 0 0 1-55.264 55.208 55.184 55.184 0 0 1-55.216-55.208 55.2 55.2 0 0 1 55.216-55.264 55.2 55.2 0 0 1 55.264 55.264z" fill="#0472AF" /><path d="M962.592 354.176a55.2 55.2 0 0 0-55.216 55.264 55.184 55.184 0 0 0 55.216 55.208V354.176z" fill="#1A8DCC" /><path d="M116.656 409.44a55.216 55.216 0 0 1-55.272 55.208A55.208 55.208 0 0 1 6.144 409.44a55.208 55.208 0 0 1 55.24-55.264 55.224 55.224 0 0 1 55.272 55.264z" fill="#0472AF" /><path d="M61.384 354.176A55.216 55.216 0 0 0 6.144 409.44a55.2 55.2 0 0 0 55.24 55.208V354.176z" fill="#0092D2" /></svg>} />
    UPGRADE TO PRO</Button> 
    </a>} {props.version && <b>{version}</b>}</>)

}

function HomeLink() {
return (<a href={'?page=vayu-sites'} title='Dashboard'><Icon icon={<svg className = "dashboard-link" fill="#fff"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M19.5 4.5h-7V6h4.44l-5.97 5.97 1.06 1.06L18 7.06v4.44h1.5v-7Zm-13 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3H17v3a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h3V5.5h-3Z"></path></svg>} />
</a>)
}

function Logo() {
    return ( <div className="logo aisb-logo">

    <a href={window.location.href}><img src={AISB.rootPath+"inc/assets/img/logo-vayu.png"} alt="Vayu Logo" /></a>
    </div>)
    }

// Set default props
Upgrade.defaultProps = {
    styles:{color: "#1e1e1e",
    background: "#fbd15b"},
    btn:true,
    version:true
  };


  export { Upgrade,HomeLink,Logo };
