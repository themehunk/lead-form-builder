import React, { useState } from 'react';
import { DragDropContext,Droppable ,Draggable } from 'react-beautiful-dnd';

import TaskCard from './TaskCard';
import {columnsFromBackend} from './TaskData';

const DragDrop = () => {
  const initialData = {
    items: [
      { id: 'item-1', content: 'Item 1' },
      { id: 'item-2', content: 'Item 2' },
      { id: 'item-3', content: 'Item 3' }
    ]
  };
    

      // const onBeforeCapture = useCallback(() => {
      //   /*...*/
      // }, []);
      // const onBeforeDragStart = useCallback(() => {
      //   /*...*/
      // }, []);
      // const onDragStart = useCallback(() => {
      //   /*...*/
      // }, []);
      // const onDragUpdate = useCallback(() => {
      //   /*...*/
      // }, []);
      // const onDragEnd = useCallback(() => {
      //   // the only one that is required
      // }, []);

      const [data, setData] = useState(initialData);

      
      const addItem = (index) => {
        const newItem = {
          id: `item-${data.items.length + 1}`,
          content: `Item ${data.items.length + 1}`
        };
        setData(prevData => {
          const updatedItems = [...prevData.items];
          updatedItems.splice(index, 0, newItem);
          return { items: updatedItems };
        });
      };
  const addTextField = (index) => {

    console.log(index);
    const newItem = {
      id: `item-${data.items.length + 1}`,
      content: `Item ${data.items.length + 1}`,
      type:"text"
    };
    setData(prevData => {
      const updatedItems = [...prevData.items];
      updatedItems.splice(index, 0, newItem);
      return { items: updatedItems };
    });
  };


  const addTextAreaField = (index) => {
    const newItem = {
      id: `item-${data.items.length + 1}`,
      content: `Item ${data.items.length + 1}`,
      type:"textarea"
    };
    setData(prevData => {
      const updatedItems = [...prevData.items];
      updatedItems.splice(index, 0, newItem);
      return { items: updatedItems };
    });
  };


  const onDragEnd = result => {

    console.log(result.destination.index);
    if (result.draggableId==='text')
    {addTextField(result.destination.index) 
      return;
    }

    if (result.draggableId==='textarea')
    {addTextAreaField() 
      return;
    }
    if (!result.destination) return; // dropped outside the list
    const items = Array.from(data.items);
    const [reorderedItem] = items.splice(result.source.index, 1);
    items.splice(result.destination.index, 0, reorderedItem);

    setData({ items });
  };
      return (
        <div>
      


        <DragDropContext onDragEnd={onDragEnd}>

        
        <Droppable droppableId="droppable">
          {(provided) => (
            <div {...provided.droppableProps} ref={provided.innerRef}>


        <Draggable  key={1} draggableId="text" index={1}>

                {(provided) => (
              <div
                ref={provided.innerRef}
                {...provided.draggableProps}
                {...provided.dragHandleProps}
              >
                <div  onClick={addItem}
              >Add Text Field</div>
              </div>
            )}


         
          </Draggable>

          <Draggable  key={2} draggableId="textarea" index={2}>

{(provided) => (
<div
ref={provided.innerRef}
{...provided.draggableProps}
{...provided.dragHandleProps}
>
<div  onClick={addItem}
>Add textarea</div>
</div>
)}



</Draggable>

          {provided.placeholder}
              </div>
      )}
          </Droppable>






          <Droppable droppableId="droppable">
            {(provided) => (
              <div {...provided.droppableProps} ref={provided.innerRef}>
                {data.items.map((item, index) => (
                  <Draggable key={item.id} draggableId={item.id} index={index}>
                    {(provided) => (


                         
                      
                    <div
                        ref={provided.innerRef}
                        {...provided.draggableProps}
                        {...provided.dragHandleProps}
                      >

                      {item.type === "text" &&  <input type={item.type} id={item.id} className={item.id}></input>}
                      {item.type === "textarea" &&  <textarea type={item.type} id={item.id} className={item.id}></textarea>}


                        {item.content}
                      </div>
                    )}
                  </Draggable>
                ))}
                {provided.placeholder}
              </div>
            )}
          </Droppable>
        </DragDropContext>
      </div>
      );
};

export default DragDrop;