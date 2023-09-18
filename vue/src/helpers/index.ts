import translations from "../../public/translations"

export function makeRequest(string: string) {
  return import.meta.env.VITE_SERVER_URL + string;
}

export function bootstrapTableTranslateFields(
  fields: Array<{ [key: string]: string }>
) {
  return fields.map((field) => ({
    ...field,
    key: field.name,
    name: field.label,
  }));
}

export function fixHeader(string: string) {
  if (!string) {
    return "";
  }

  return translate(string.charAt(0).toUpperCase() + string.slice(1));
}

export function translate(string: string) {
  return translations?.[string];
}
