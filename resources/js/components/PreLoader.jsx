import React from 'react';

const PreLoader = ({ progress }) => {
    const textStyle = {
        textAlign: 'center',
        marginTop: '5px'
    };

    return (
        <div style={textStyle}>
            Carregando... {progress}%
        </div>
    );
};

export default PreLoader;
