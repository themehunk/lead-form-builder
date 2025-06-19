/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import {DataForm} from '@wordpress/dataviews';

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
    const [inputValue, setInputValue] = useState('hello');

	const {
        formid,
        title,
        randerForm,
        formList,
        data = {
          id: 1,
          title: 'Title',
          placeholder: 'Admin',
          date: '2012-04-23T18:25:43.511Z',
      }
       } = attributes;


       
    
    const onChange = ( edits ) => {
        /*
         * edits will contain user edits.
         * For example, if the user edited the title
         * edits will be:
         *
         * {
         *   title: 'New title'
         * }
         *
         */
      console.log(edits,attributes);

      setAttributes({ data: {
        ...attributes.data, // Preserve old data
        ...edits,        // Add or update new data
    }})

    };

    console.log('Data',data);

    return (
		<div { ...useBlockProps() }>
        <DataForm
  data={{
    title: data.title,
    placeholder:data.placeholder,
    author: 1,
    birthdate: '1950-02-23T12:00:00',
    date:data.date,
    order: 2,
    reviewer: 'fulano',
    status: 'draft',
    sticky: false,
    title: data.title
  }}
  fields={[
    {
      id: 'title',
      label: 'Title',
      type: 'text'
    },
    {
      id: 'placeholder',
      label: 'Placeholder',
      type: 'text'
    },
    {
      id: 'order',
      label: 'Order',
      type: 'integer'
    },
    {
      id: 'date',
      label: 'Date',
      type: 'datetime'
    },
    {
      elements: [
        {
          label: 'Jane\'s birth date',
          value: '1970-02-23T12:00:00'
        },
        {
          label: 'John\'s birth date',
          value: '1950-02-23T12:00:00'
        }
      ],
      id: 'birthdate',
      label: 'Date as options',
      type: 'datetime'
    },
    {
      elements: [
        {
          label: 'Jane',
          value: 1
        },
        {
          label: 'John',
          value: 2
        }
      ],
      id: 'author',
      label: 'Author',
      type: 'integer'
    },
    {
      Edit: 'radio',
      elements: [
        {
          label: 'Fulano',
          value: 'fulano'
        },
        {
          label: 'Mengano',
          value: 'mengano'
        },
        {
          label: 'Zutano',
          value: 'zutano'
        }
      ],
      id: 'reviewer',
      label: 'Reviewer',
      type: 'text'
    },
    {
      elements: [
        {
          label: 'Draft',
          value: 'draft'
        },
        {
          label: 'Published',
          value: 'published'
        },
        {
          label: 'Private',
          value: 'private'
        }
      ],
      id: 'status',
      label: 'Status',
      type: 'text'
    },
    {
      id: 'password',
      isVisible: () => {},
      label: 'Password',
      type: 'text'
    },
    {
      Edit: () => {},
      id: 'sticky',
      label: 'Sticky',
      type: 'integer'
    }
  ]}
  form={{
    fields: [
      'title',
      'placeholder',
      'order',
      {
        id: 'sticky',
        labelPosition: 'side',
        layout: 'regular'
      },
      'author', 
      'reviewer',
      'password',
      'date',
      'birthdate'
    ],
    labelPosition: 'side',
   type: undefined
  }}
  onChange={ onChange }
/>

 </div>
	);
}
