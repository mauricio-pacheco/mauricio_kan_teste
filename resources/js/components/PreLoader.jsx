import React from 'react';

const PreLoader = () => {
    const textStyle = {
        textAlign: 'center',
        marginTop: '5px'
    };

    return (
        <div style={textStyle}>
            Carregando...
        </div>
    );
};

export default PreLoader;
