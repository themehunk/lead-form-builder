
import { createRoot, render, createElement,useState,useEffect } from '@wordpress/element';
// import Header from './template/header';
// import BuilderIcon from './template/buildericon';
// import AiBuilder from './template/aibuilder';
import { getQueryArg } from '@wordpress/url';
import DragDrop from './template/builder/dragdrop';
// import Dashboard from './template/dashboard';
import './template/builder/style.css'


export default function App() {

  const [ currentUrl, setCurrentUrl ] = useState(window.location.href);
  const [ pageChange, setpageChange ] = useState(null);

  const [ builder, setBuilder ] = useState(null);

  const builderHide = (builder_rs) => {
    setBuilder(builder_rs);
  }



  useEffect(() => {
    if(getQueryArg( currentUrl,'template' ) ==='step'){
      setpageChange(true);
     } 
    else{
      setpageChange(false);
    }


  }, []);

    return (
      <div className="App">
        <DragDrop />
     {/* {pageChange && <div className="aisb-app-wrap" >


            <Header/>
        <div className='heading'>
        <h1 >Select Gutenberg Template to Build Your Website ! </h1>
        </div>
        <BuilderIcon builderclick = {(builder_rs)=>builderHide(builder_rs)}/>


        <AiBuilder builder = {builder} />
        </div> }

        {pageChange===false && <Dashboard />} */}
      </div>
    );
  }