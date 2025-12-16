import React from "react";

const TestFlow = ({ testGroups, setTestGroups }) => {
    const toggle = (key) => {
        setTestGroups((prev) => ({
            ...prev,
            [key]: !prev[key],
        }));
    };

    return (
        <div className="card mt-3">
            <div className="card-header fw-bold">Test Flows</div>
            <div className="card-body">
                {[
                    ["login", "Login"],
                    ["casino", "Casino Flows"],
                    ["live", "Live Flows"],
                    ["bonus", "Bonus Flows"],
                    ["error", "Error Handling"],
                    ["gameslink", "Gameslink Features"],
                    ["logout", "Logout"],
                ].map(([key, label]) => (
                    <div className="form-check mb-2" key={key}>
                        <input
                            className="form-check-input"
                            type="checkbox"
                            checked={testGroups[key]}
                            onChange={() => toggle(key)}
                        />
                        <label className="form-check-label">
                            {label}
                        </label>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default TestFlow;