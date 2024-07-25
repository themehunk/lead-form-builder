import { useState,useEffect } from '@wordpress/element';
import { useSelector, useDispatch } from 'react-redux';
import {tmplLodaing,stepFour} from '../actions';

const ThemehunkSSEImport = {
    complete: {
      posts: 0,
      media: 0,
      users: 0,
      comments: 0,
      terms: 0,
    },
  
    updateDelta: function (type, delta) {
      this.complete[ type ] += delta;
      
      var self = this;
      requestAnimationFrame(function () {
        self.render();
      });
    },
    updateProgress: function ( type, complete, total ) {
      var text = complete + '/' + total;
  
      if( 'undefined' !== type && 'undefined' !== text ) {
        total = parseInt( total, 10 );
        if ( 0 === total || isNaN( total ) ) {
          total = 1;
        }
        var percent = parseInt( complete, 10 ) / total;
        var progress     = Math.round( percent * 100 ) + '%';
        var progress_bar = percent * 100;
      }
    },
    render: function () {
      var types = Object.keys( this.complete );
      var complete = 0;
      var total = 0;
  
      for (var i = types.length - 1; i >= 0; i--) {
        var type = types[i];


        this.data.count[ type ] && this.updateProgress( type, this.complete[ type ], this.data.count[ type ] );

        
  
        complete += this.complete[ type ];
        total += this.data.count[ type ];
      }
  
      this.updateProgress( 'total', complete, total );
    }
  };
  
  

export default function ImportAPI(props) {
  const dispatch = useDispatch();
  const lodaingMsg = useSelector((state)=>state.tmplInstall);
  const pageStep = useSelector((state)=>state.stepLoad);


  const [apiUrl, setApiUrl] = useState(props.apiurl);
  const [apiData, setApiData] = useState(null);
  const [updateStart, setupdateStart] = useState(false);
  const [ajaxUrl, setAjaxUrl] = useState(AISB.ajaxurl);


    /*** All Import data
     *  customizer,option,xml
     */
    const getImportData = async () =>{
          const dataToSend = { data: apiUrl }; // Customize the data to send
           const response = await fetch(ajaxUrl, {
            method: 'POST',
            body: new URLSearchParams({
                action: 'vayu_blocks_sites_ajax_handler_data', // Specify the WordPress AJAX action
                data: JSON.stringify(dataToSend), // Convert the data to JSON and send it
            }),
        });
        
        const jsonData = await response.json();
         setApiData(jsonData.data);
        console.log('Start...');
        dispatch(tmplLodaing('Importing Content'));

      importXml(jsonData.data.xml);
      }


      /** Xml Data Import
       * function 1: importXml()
       * function 2: hendelXmlImport() xml data import all images, files,post and pages
       */

const hendelXmlImport = async (xml_data) => {
    ThemehunkSSEImport.data = xml_data;
    ThemehunkSSEImport.render();
    const evtSource = new EventSource( ThemehunkSSEImport.data.url );
  
    evtSource.onmessage = function ( message ) {
      var data = JSON.parse( message.data );
      switch ( data.action ) {
        case 'updateDelta':
          dispatch(tmplLodaing('Importing - '+data.type));
            ThemehunkSSEImport.updateDelta( data.type, data.delta );
          break;

        case 'complete':  
           evtSource.close();
          // 2. Pass - Import XML though "Source Event".
          // console.log('XML importing completed.');
          dispatch(tmplLodaing('Importing Design & Logo'));

          setupdateStart(true);
      //  $(document).trigger( 'themehunk-sites-import-xml-success' );
          break;
      }
    };


    evtSource.addEventListener( 'log', function ( message ) {
      var data = JSON.parse( message.data );
    
    });
  
  }
  
const importXml = async (xml_url) =>{
        try {
      
          const dataToSend = { data: xml_url }; // Customize the data to send
          const response = await fetch(ajaxUrl, {
            method: 'POST',
            body: new URLSearchParams({
                action: 'vayu_blocks_sites_ajax_import_xml', // Specify the WordPress AJAX action
                data: JSON.stringify(dataToSend), // Convert the data to JSON and send it
            }),
        }).then(response => response.json())
            .then(xml_data => {
                // Handle the AJAX response
                hendelXmlImport(xml_data.data);

            })
            .catch(error => {
                // Handle errors
                console.error('Error in AJAX request:', error);
            });
        } catch (error) {
            console.error('Error fetching data:', error);
          }          
      }
     

      /*** Customizer Data import
       * function : importCustomizer();
       */

const importCustomizer = async () =>{

    try {
  
      const dataToSend = { data: apiData.customizer }; // Customize the data to send
      const response = await fetch(ajaxUrl, {
        method: 'POST',
        body: new URLSearchParams({
            action: 'vayu_blocks_sites_ajax_cutomizer', // Specify the WordPress AJAX action
            data: JSON.stringify(dataToSend), // Convert the data to JSON and send it
        }),
    })

    .then(response => response.json())
        .then(customizer_data => {
            // Handle the AJAX respons            

            return customizer_data;
            
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


  
      /*** Customizer Data import
       * function : importCustomizer();
       */

const importOptions = async () =>{

  try {

    const dataToSend = { data: apiData.option }; // Customize the data to send
   await fetch(ajaxUrl, {
      method: 'POST',
      body: new URLSearchParams({
          action: 'vayu_blocks_sites_aimport_options', // Specify the WordPress AJAX action
          data: JSON.stringify(dataToSend), // Convert the data to JSON and send it
      }),
  }).then(response => response.json())
      .then(options_dat => {
          // Handle the AJAX respons
    
          return new Promise((resolve, reject) => {
            // console.log(options_dat);
            
            resolve();
          })
          
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



      /*** Customizer Data import
       * function : importCustomizer();
       */

      const importWidgets = async () =>{
    
        try {
          const dataToSend = { data: apiData.widgets }; // Customize the data to send
         await fetch(ajaxUrl, {
            method: 'POST',
            body: new URLSearchParams({
                action: 'vayu_blocks_sites_import_widgets', // Specify the WordPress AJAX action
                data: JSON.stringify(dataToSend), // Convert the data to JSON and send it
            }),
        }).then(response => response.json())
            .then(options_dat => {
                // Handle the AJAX response      
                  // console.log(' Widgets-success');
                  
                return options_dat;
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


      const interval = () => setTimeout(() => {
        dispatch(tmplLodaing('Finishing Setup...'));
      }, 3000);

      const executeStart = async () => {
        setupdateStart(false);
        try {
          dispatch(tmplLodaing('Updating all Settings.'));
         await importCustomizer();
          dispatch(tmplLodaing('Importing Footer & Sidebar...'));
          await importOptions();
          dispatch(tmplLodaing('Getting Things Done...'));
          interval();
          await importWidgets();         
          setApiData(null);
          dispatch(stepFour(true));
          console.log('Completed.');
          return;
        } catch (error) {
          console.error('Error executing functions:', error);
        }

      };

     


      useEffect(() => {
        getImportData();       
      }, []); // 👈️ empty dependencies array

     updateStart && executeStart();
    //  apiData && executeStart();

  

    return (<span className='loading-msg'>{lodaingMsg}</span>);

}