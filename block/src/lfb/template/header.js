import { Icon} from '@wordpress/icons';
import { Upgrade,HomeLink, Logo } from '../aisb';
export default function Header() {
  const currentURL = window.location.href;
    return (
      <div className="header">
        <header>
           <Logo/>
            
            <div className="header-text">
            <Upgrade/>
            <HomeLink/>
            </div>
        </header>
      </div>
    );
  }