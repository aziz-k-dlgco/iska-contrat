import React, { useState, useRef, useEffect } from "react";

function InputDropdown({ name, label, data, onChange, isAnother, disabled }) {
  const [selected, setSelected] = useState(null);
  const [otherValue, setOtherValue] = useState("");
  const inputRef = useRef(null);
  const [options, setOptions] = useState([]);

  useEffect(() => {
    if (data) {
      if (data.length > 0) {
        setSelected(data[0].value);
      }

      setOptions(data);
      if (isAnother) {
        // Merge the "Other" option to the end of the list
        setOptions([...data, { value: "Other", label: "Other" }]);
      }
    }

    console.log(options);
  }, [data]);

  useEffect(() => {
    if (selected === "other" && inputRef.current) {
      inputRef.current.focus();
    }
  }, [selected]);

  useEffect(() => {
    onChange({ target: { name, value: otherValue } });
  }, [otherValue]);

  useEffect(() => {
    onChange({ target: { name, value: selected } });
  }, [selected]);

  function handleChange(event) {
    const { value } = event.target;
    setSelected(value);
    if (value === "@@@") {
      setOtherValue("");
    }
  }

  function handleOtherChange(event) {
    setOtherValue(event.target.value);
  }

  return (
    <>
      <label className="block text-sm font-medium mb-1" htmlFor={name}>
        {label}
      </label>
      <div className="flex items-center">
        <select
          id={name}
          className={`form-select w-full inline-block mr-2 ${
            disabled ? "bg-gray-200" : ""
          }`}
          onChange={handleChange}
          disabled={disabled}
        >
          {options.map((item, index) => (
            <option key={index} value={item.value}>
              {item.label}
            </option>
          ))}
        </select>

        {selected === "@@@" && (
          <input
            ref={inputRef}
            type="text"
            value={otherValue}
            className="form-input inline-block"
            onChange={handleOtherChange}
          />
        )}
      </div>
    </>
  );
}

export default InputDropdown;
