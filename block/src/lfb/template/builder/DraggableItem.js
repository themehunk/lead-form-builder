import React from 'react';

const DraggableItem = ({ item, onDragStart }) => {
  const handleDragStart = (e) => {
    e.dataTransfer.setData('text/plain', item); // Set the data being dragged
    onDragStart();
  };

  return (
    <div
      draggable
      onDragStart={handleDragStart}
    >
      {item}
    </div>
  );
};

export default DraggableItem;
