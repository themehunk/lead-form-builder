import React from 'react';

const DroppableArea = ({ onDrop, onDragOver }) => {
  const handleDrop = (e) => {
    e.preventDefault();
    const data = e.dataTransfer.getData('text/plain'); // Get the data being dropped
    onDrop(data);
  };

  return (
    <div
      onDrop={handleDrop}
      onDragOver={onDragOver}
    >
      Droppable Area
    </div>
  );
};

export default DroppableArea;
