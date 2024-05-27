import React from 'react';

const PreLoader = ({ progress }) => {
    const containerStyle = {
        textAlign: 'center',
        marginTop: '5px'
    };

    const progressBarStyle = {
        width: '100%',
        height: '20px',
        borderRadius: '10px',
        backgroundColor: '#eee',
        marginTop: '5px'
    };

    const progressStyle = {
        width: `${progress}%`,
        height: '100%',
        borderRadius: '10px',
        backgroundColor: '#007bff',
    };

    return (
        <div style={containerStyle}>
            <div>
                Carregando... {progress}%
            </div>
            <div style={progressBarStyle}>
                <div style={progressStyle}></div>
            </div>
        </div>
    );
};

export default PreLoader;
