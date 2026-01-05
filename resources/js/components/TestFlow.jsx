import React, { useEffect } from "react";

const TestFlow = ({ testGroups, setTestGroups }) => {
    const lockedTrue = {
        login: true,
        logout: true,
    };

    useEffect(() => {
        setTestGroups((prev) => ({
            ...prev,
            ...lockedTrue,
        }));
    }, []);

    const toggle = (key) => {
        if (key in lockedTrue) return;

        setTestGroups((prev) => ({
            ...prev,
            [key]: !prev[key],
        }));
    };

    const flows = [
        ["login", "Login (required)"],
        ["casino", "Casino Flows"],
        ["live", "Live Flows"],
        ["bonus", "Bonus Flows"],
        ["error", "Error Handling"],
        ["gameslink", "Gameslink Features"],
        ["logout", "Logout (required)"],
    ];

    return (
        <div className="card mt-3">
            <div className="card-header fw-bold">Test Flows</div>
            <div className="card-body">
                {flows.map(([key, label]) => {
                    const isLocked = key in lockedTrue;

                    return (
                        <div className="form-check mb-2" key={key}>
                            <input
                                className="form-check-input"
                                type="checkbox"
                                checked={!!testGroups[key]}
                                disabled={isLocked} // ✅ unchangeable
                                onChange={() => toggle(key)} // ✅ no-op for locked
                                id={`flow-${key}`}
                            />
                            <label
                                className="form-check-label"
                                htmlFor={`flow-${key}`}
                            >
                                {label}
                            </label>
                        </div>
                    );
                })}
            </div>
        </div>
    );
};

export default TestFlow;
