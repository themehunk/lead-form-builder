/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { useEffect,useState,RawHTML  } from '@wordpress/element';

import { Panel, PanelBody,TextControl,SelectControl,Button,Spinner  } from '@wordpress/components';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps,RichText,InspectorControls  } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */


export default function Edit({ attributes, setAttributes,isSelected  }) {
    const [url,setUrl] = useState(false);
    const [isform,seIsForm] = useState(false);
    const [loader,setLoader] = useState(true);
    const useSelect = wp.data.select( 'core' ).getSite();
    url===false && useSelect && setUrl(useSelect.url);

	const {
        formid,
        title,
        randerForm,
        formList
      } = attributes;


    // const el = wp.element.createElement;
    // const htmlToElem = (html) => wp.element.RawHTML({ children: html });
  
	const lfbData = async () =>{

        try {
      
          const dataToSend = { data: formid,title:title}; // Customize the data to send

          const response = await fetch(lfbScriptData.ajax_url, {
            method: 'POST',
            headers: {
              'X-WP-Nonce': lfbScriptData.security,
          },
            body: new URLSearchParams({
                action: 'lead_form_builderr_data', // Specify the WordPress AJAX action
                security: lfbScriptData.security,
                data: JSON.stringify(dataToSend), // Convert the data to JSON and send it
            }),
        }).then(response => response.json())
            .then(data => {
              data.data.lfb_form && data.data.lfb_form.length && (seIsForm(true));
				    setAttributes(  { formList:data.data.lfb_form,randerForm: data.data.lfb_rander } );
            setLoader(false);

            })
            .catch(error => {
                // Handle errors
                console.error('Error in AJAX request:', error);
            });
        } catch (error) {
            console.error('Error fetching data:', error);
          }          
      }

      useEffect(() => {
        setLoader(true);
        lfbData(); 
     }, [formid]); // 👈️ empty dependencies array

	  useEffect(() => {
         lfbData(); 
      }, [title]); // 👈️ empty dependencies array

     const slectFormLIst = () =>{

      let defaultar = [{
        disabled: true,
        label: 'Select Form',
        value: ''
      }];

       const flsit =  formList && formList.map(function(form, i){
        const Optionformlist = { label: form.form_title, value: form.id };

         return Optionformlist;
        });
     const arr = [...defaultar,...flsit];        
        return arr;

      }

      const handleClick = (link)=>{
       window.open(`${url}/wp-admin/admin.php?page=${link}`, "_blank")
      }
	return (
		<div { ...useBlockProps() }>
  { isSelected && <InspectorControls key="setting">
             <Panel header="lfb">

             <PanelBody title="Lead Form Builder"  initialOpen={ true }>

             {isform && <SelectControl
              label="Slect Lead Form"
              value={ formid }
              options={ slectFormLIst() }
              onChange={  ( value ) => setAttributes(  { formid: value } ) }
               />}

                    {/* <TextControl
                    label="Form Title"
                    value={ title }
                    onChange={ ( value ) => setAttributes(  { title: value } ) }
                /> */}

        <Button variant="secondary" onClick={ ()=>handleClick('wplf-plugin-menu') }>Customize Lead Form</Button>
				</PanelBody>
				</Panel>
			</InspectorControls>}

      {loader && <Spinner />}
      {isform && <RawHTML>{randerForm}</RawHTML>}
      {isform===false && loader ===false && <Button variant="primary" onClick={()=>handleClick('add-new-form') }>Create New Form</Button>}
		</div>
	);
}
